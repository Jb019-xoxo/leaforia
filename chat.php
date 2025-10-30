<?php
session_start();
header("Content-Type: application/json");
include 'db.php';

// === 1️⃣ Get user message ===
$input = json_decode(file_get_contents("php://input"), true);
$userMessage = strtolower(trim($input["message"] ?? ""));

if (empty($userMessage)) {
    echo json_encode(["reply" => "🌿 Hello! Kumusta ka? Pwede mong sabihin ang iyong kondisyon, gaya ng 'May ubo ako' o sa Bisaya 'Naay ko ubo' o sa Kapampangan 'Mekakasingku ku'."]);
    exit;
}

// === 2️⃣ Detect if user wants translation of the last response ===
$translateLang = null;
$languageKeywords = [
    "tagalog" => "Tagalog",
    "filipino" => "Tagalog",
    "bisaya" => "Cebuano",
    "cebuano" => "Cebuano",
    "ilokano" => "Ilocano",
    "ilocano" => "Ilocano",
    "kapampangan" => "Kapampangan",
    "pampango" => "Kapampangan",
    "english" => "English",
    "spanish" => "Spanish",
    "japanese" => "Japanese",
    "chinese" => "Chinese",
    "korean" => "Korean"
];

foreach ($languageKeywords as $word => $lang) {
    if (strpos($userMessage, "translate to $word") !== false || strpos($userMessage, "in $word") !== false) {
        $translateLang = $lang;
        break;
    }
}

// === 3️⃣ If translation is requested and last reply exists ===
if ($translateLang && isset($_SESSION['last_reply'])) {
    $previousReply = $_SESSION['last_reply'];

    $apiKey = "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";
    $url = "https://openrouter.ai/api/v1/chat/completions";

    $systemPrompt = "You are a multilingual translator. Translate the following herbal advice naturally into {$translateLang}. 
    Keep it warm, friendly, and culturally natural — like how a Filipino would explain health advice in that dialect.";

    $postData = [
        "model" => "gpt-4o-mini",
        "messages" => [
            ["role" => "system", "content" => $systemPrompt],
            ["role" => "user", "content" => $previousReply]
        ],
        "temperature" => 0.6,
        "max_tokens" => 400
    ];

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "Authorization: Bearer $apiKey",
            "HTTP-Referer: http://localhost",
            "X-Title: Herbal Wonders"
        ],
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($postData)
    ]);
    $response = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($response, true);

    $translated = trim($data["choices"][0]["message"]["content"] ?? "⚠️ Sorry, I couldn’t translate that right now.");
    $_SESSION['last_reply'] = $translated;

    echo json_encode(["reply" => $translated]);
    exit;
}

// === 4️⃣ Detect if user wants to clear chat ===
if (strpos($userMessage, "clear chat") !== false || strpos($userMessage, "new topic") !== false) {
    session_destroy();
    echo json_encode(["reply" => "🧘 Memory cleared. Let's start fresh! What herbal concern do you want to talk about today?"]);
    exit;
}

// === 5️⃣ Search herbs in your database ===
$keywords = explode(" ", preg_replace("/[^a-zA-Z0-9\s]/", "", $userMessage));
$searchTerms = array_filter($keywords, fn($w) => strlen($w) > 2);
if (empty($searchTerms)) {
    echo json_encode(["reply" => "🌿 Can you tell me more about your condition?"]);
    exit;
}

$conditions = implode(" OR ", array_fill(0, count($searchTerms),
    "(LOWER(name) LIKE ? OR LOWER(uses) LIKE ? OR LOWER(description) LIKE ?)"));
$sql = "SELECT name, scientificname, uses, description, precautions FROM herbs WHERE $conditions";
$stmt = $conn->prepare($sql);

$params = [];
foreach ($searchTerms as $term) {
    $like = "%$term%";
    $params[] = $like; $params[] = $like; $params[] = $like;
}
$types = str_repeat("s", count($params));
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $noData = "😔 Sorry, I couldn’t find any herbs related to that in our database. Try another keyword or symptom.";
    $_SESSION['last_reply'] = $noData;
    echo json_encode(["reply" => $noData]);
    exit;
}

// === 6️⃣ Build herbal context ===
$herbalContext = "";
while ($row = $result->fetch_assoc()) {
    $herbalContext .= "🌿 " . ucfirst($row['name']) . " (" . $row['scientificname'] . ")\n";
    if (!empty($row['uses'])) $herbalContext .= "• Uses: {$row['uses']}\n";
    if (!empty($row['description'])) $herbalContext .= "• Description: {$row['description']}\n";
    if (!empty($row['precautions'])) $herbalContext .= "• Precautions: {$row['precautions']}\n";
    $herbalContext .= "\n";
}
$conn->close();

// === 7️⃣ Generate warm AI response based on DB ===
$apiKey = "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";
$url = "https://openrouter.ai/api/v1/chat/completions";

$systemPrompt = "
You are 'Herbal Wonders Assistant', a caring Filipino herbal guide.
Base your answer only on the herbal data provided below — never invent new herbs.
Be empathetic, warm, and conversational, sometimes mixing light Filipino, Bisaya, Ilokano, or Kapampangan tones for friendliness.
Encourage the user to rest and consult professionals if symptoms persist.

Herbal Data:
$herbalContext
";

$postData = [
    "model" => "gpt-4o-mini",
    "messages" => [
        ["role" => "system", "content" => $systemPrompt],
        ["role" => "user", "content" => $userMessage]
    ],
    "temperature" => 0.8,
    "max_tokens" => 400
];

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "Authorization: Bearer $apiKey",
        "HTTP-Referer: http://localhost",
        "X-Title: Herbal Wonders"
    ],
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($postData)
]);
$response = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    echo json_encode(["reply" => "⚠️ Network error: $error"]);
    exit;
}

$data = json_decode($response, true);
$reply = trim($data["choices"][0]["message"]["content"] ?? "🌿 Here's what I found:\n\n" . $herbalContext);

// Save reply for translation memory
$_SESSION['last_reply'] = $reply;

echo json_encode(["reply" => $reply]);
?>
