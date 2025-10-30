// ===== OPEN VIEW MODAL =====
function openViewModal(name, scientific, characteristics, uses, description, precautions, image, youtube, videoPath) {
    document.getElementById("modalName").innerText = name;
    document.getElementById("modalScientific").innerText = scientific;
    document.getElementById("modalCharacteristics").innerText = characteristics;
    document.getElementById("modalUses").innerText = uses;
    document.getElementById("modalDescription").innerText = description;
    document.getElementById("modalPrecautions").innerText = precautions;

    const modalImage = document.getElementById("modalImage");
    modalImage.src = image || "";
    modalImage.style.display = image ? "block" : "none";

    const youtubeLink = document.getElementById("modalYoutube");
    if (youtube) { youtubeLink.href = youtube; youtubeLink.innerText = youtube; }
    else { youtubeLink.href = "#"; youtubeLink.innerText = "N/A"; }

    const video = document.getElementById("modalVideo");
    const source = video.querySelector("source");
    if (videoPath) {
        source.src = videoPath;
        video.load();
        video.style.display = "block";
    } else {
        video.style.display = "none";
    }

    document.getElementById("herbModal").style.display = "flex";
}

// ===== OPEN CREATE MODAL =====
function openCreateModal() { document.getElementById("createModal").style.display = "flex"; }

// ===== OPEN EDIT MODAL =====
function openEditModal(id, name, scientific, characteristics, uses, description, precautions, image, youtube, videoPath) {
    document.getElementById("edit_id").value = id;
    document.getElementById("edit_name").value = name;
    document.getElementById("edit_scientific").value = scientific;
    document.getElementById("edit_characteristics").value = characteristics;
    document.getElementById("edit_uses").value = uses;
    document.getElementById("edit_description").value = description;
    document.getElementById("edit_precautions").value = precautions;
    document.getElementById("edit_youtube").value = youtube;

    // Image preview
    const imgPreview = document.getElementById("editImagePreview");
    if (image) { imgPreview.src = image; imgPreview.style.display = "block"; }
    else { imgPreview.style.display = "none"; }

    // Video preview
    const videoPreview = document.getElementById("editVideoPreview");
    const videoSource = document.getElementById("editVideoSource");
    if (videoPath) {
        videoSource.src = videoPath;
        videoPreview.load();
        videoPreview.style.display = "block";
    } else {
        videoPreview.style.display = "none";
    }

    document.getElementById("editModal").style.display = "flex";
}

// ===== CLOSE MODAL =====
function closeModal(id) { document.getElementById(id).style.display = "none"; }


