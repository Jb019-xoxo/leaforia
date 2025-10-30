/* ===== GLOBAL ===== */




/* Sidebar Title */
.sidebar h1 {
  font-size: 22px;
  margin-bottom: 30px;
   border-bottom: 2px solid white; /* changed from green to white */
  padding-bottom: 12px;
  width: 100%;
  font-family: "Poppins", sans-serif;
  letter-spacing: 1px;
  color: #a5d6a7;
  text-shadow: 1px 1px 4px rgba(0,0,0,0.6);
}

/* Navigation */
/* Navigation */
.sidebar nav {
  display: flex;
  flex-direction: column;
  gap: 20px; /* more space between links */
  width: 100%;
  align-items: center; /* ✅ center the links */
}

/* Nav Links */
.sidebar nav a {
  display: flex;
  align-items: center;
  justify-content: center; /* ✅ center text */
  font-size: 18px; /* ✅ make text bigger */
  padding: 14px 18px; /* more padding */
  color: #f1f1f1;
  text-decoration: none;
  border-radius: 10px;
  font-weight: 700; /* bolder */
  font-family: "Poppins", sans-serif;
  width: 80%; /* keep links aligned */
  transition: all 0.3s ease;
}

/* Hover Effect */
.sidebar nav a:hover {
  background: #2e7d32;
  transform: scale(1.1); /* slightly bigger on hover */
  box-shadow: 0 4px 12px rgba(46, 125, 50, 0.5);
}


/* Active/Selected Link */
.sidebar nav a.active {
  background: #1b5e20;
  color: #c8e6c9;
  box-shadow: inset 0 0 10px rgba(0,0,0,0.4);
}


/* ===== MAIN CONTENT ===== */
.content {
  margin-left: 240px;
  padding: 20px 20px 20px 60px;
}

/* ===== SEARCH FORM ===== */
.search-container {
  display: flex;
  justify-content: center;
  margin: 30px auto;
  padding: 25px 20px;
  max-width: 800px;
  background: #fff;
  border: 1px solid #ddd;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

/* ===== SEARCH FORM ===== */
.search-form {
  display: flex;
  align-items: center;
  width: 100%;
  
}

.search-form input {
  flex: 1;
  height: 55px;              /* keep the same height */
  padding: 6px 12px;
  border: 3px solid #2e7d32; /* ✅ thicker border */
  border-radius: 8px 0 0 8px;
  font-size: 18px;
  outline: none;
  box-sizing: border-box;    /* ✅ prevents resizing from border thickness */
}

.search-form input:focus {
  border-color: #256628;
  box-shadow: 0 0 5px rgba(46,125,50,0.5);
}


.search-form button {
  width: 150px;
  height: 55px;
  border: none;
  border-radius: 0 8px 8px 0;
  background: #2e7d32;
  color: white;
  font-size: 20px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-left: 0px; 
  margin-right: 10px;/* ✅ moves button to the right */
  font-weight: bold; /* ✅ text is bold */

}

.search-form button:hover {
  background: #256628;
}

/* ===== CARDS CONTAINER ===== */


/* Responsive adjustments */
@media screen and (max-width: 1200px) {
  .cards-container { grid-template-columns: repeat(3, 1fr); }
}
@media screen and (max-width: 900px) {
  .cards-container { grid-template-columns: repeat(2, 1fr); }
}
@media screen and (max-width: 600px) {
  .cards-container { grid-template-columns: 1fr; padding-left: 20px; }
}





/* ===== MODAL ===== */
.modal {
  display: none;
  position: fixed;
  z-index: 9999;
  left: 0; top: 0;
  width: 100%; height: 100%;
  background: rgba(0,0,0,0.7);
  justify-content: center;
  align-items: center;
}

.modal-content {
  background: #fff;
  border-radius: 10px;
  padding: 20px;
  max-height: 90vh;
  overflow-y: auto;
  position: relative;
}

/* View modal */
#herbModal .modal-content {
  width: 70%;
  max-width: 900px;
  display: flex;
  flex-direction: column;
}

.modal-body {
  display: flex;
  gap: 20px;
  margin-top: 10px;
}

.modal-info {
  flex: 2;
}

.modal-info p {
  margin: 6px 0;
}

.modal-image {
  flex: 1;
}

/* ===== MODERN CREATE/EDIT MODAL ===== */ 
.modal-content.form-modal {
  width: 900px;          /* ✅ wider modal */
  max-width: 95%;        /* ✅ ensures it fits smaller screens */
  background: #ffffff;
  padding: 30px;
  border-radius: 15px;
  display: flex;
  flex-direction: column;
  gap: 25px;             /* ✅ more spacing between sections */
  font-family: 'Arial', sans-serif;
  border: 3px solid #2e7d32;
  box-shadow: 0 6px 18px rgba(46,125,50,0.3);
  position: relative;
  animation: fadeIn 0.3s ease-in-out;
  box-sizing: border-box; /* ✅ include padding in width */
}

/* Mobile responsiveness */
@media screen and (max-width: 700px) {
  .modal-content.form-modal {
    width: 90%;
    padding: 20px;
  }
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-20px); }
  to { opacity: 1; transform: translateY(0); }
}

/* Input group with label above */
.input-group {
  display: flex;
  flex-direction: column;
  gap: 10px;             /* space between label and input */
  margin-bottom: 20px;   /* space between input groups */
}

.input-group label {
  font-weight: bold;
  font-size: 14px;
  color: #2e7d32;
  letter-spacing: 0.5px;
}

/* Form takes full width */
.form-modal form {
  width: 100%;
  display: flex;
  flex-direction: column;
  gap: 18px;
  box-sizing: border-box;
}

.form-modal input,
.form-modal textarea {
  width: 100%;
  padding: 14px 16px;    /* padding inside inputs */
  border: 2px solid #2e7d32;
  border-radius: 12px;
  font-size: 15px;
  outline: none;
  background: #fff;
  color: #333;
  transition: 0.3s;
  resize: vertical;
  box-shadow: 0 3px 8px rgba(46,125,50,0.15);
  box-sizing: border-box; /* ✅ ensures padding doesn't break width */
}

.form-modal input:focus,
.form-modal textarea:focus {
  border-color: #1b4d1e;
  box-shadow: 0 0 10px rgba(27, 77, 30, 0.4);
}

/* Buttons */
.btn-submit {
  background: linear-gradient(135deg, #2e7d32, #256628);
  color: white;
  padding: 12px 20px;
  border-radius: 12px;
  border: none;
  cursor: pointer;
  font-weight: bold;
  font-size: 15px;
  transition: 0.3s;
  margin-top: 10px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.3);
}

.btn-submit:hover {
  background: #1b4d1e;
  transform: scale(1.05);
}

.btn.cancel {
  background: #d32f2f;
  color: white;
  padding: 12px 20px;
  border-radius: 12px;
  text-decoration: none;
  display: inline-block;
  text-align: center;
  margin-top: 10px;
  font-weight: bold;
  transition: 0.3s;
  box-shadow: 0 4px 12px rgba(0,0,0,0.3);
}

.btn.cancel:hover {
  background: #b71c1c;
  transform: scale(1.05);
}

/* Previews */
#editImagePreview,
#editVideoPreview {
  width: 100%;
  max-height: 200px;
  margin-top: 15px; 
  border: 2px solid #2e7d32;
  border-radius: 12px;
  object-fit: cover;
  box-shadow: 0 4px 12px rgba(46, 125, 50, 0.25);
}

/* Title */
.form-modal h2 {
  margin: 0;
  text-align: center;
  color: #2e7d32;
  font-weight: bold;
  font-size: 28px;
  border-bottom: 3px double #2e7d32;
  padding-bottom: 12px;
  margin-bottom: 20px;
  text-transform: uppercase;
  text-shadow: 1px 1px 3px rgba(46, 125, 50, 0.4);
}


