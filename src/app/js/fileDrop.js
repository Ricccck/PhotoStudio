const dropArea = document.getElementById("drop-area");
const fileInput = document.getElementById("image");
const preview = document.getElementById("preview");
const form = document.getElementById("form");

dropArea.addEventListener("dragover", (e) => {
  e.preventDefault();
  dropArea.classList.add("highlight");
});

dropArea.addEventListener("dragleave", (e) => {
  e.preventDefault();
  dropArea.classList.remove("highlight");
});

dropArea.addEventListener("drop", (e) => {
  e.preventDefault();
  dropArea.classList.remove("highlight");

  const files = e.dataTransfer.files;
  handleFiles(files);
});

fileInput.addEventListener("change", (e) => {
  const files = e.target.files;
  handleFiles(files);
});

const handleFiles = (files) => {
  preview.innerHTML = "";

  fileInput.files = files;

  for (let file of files) {
    if (file.type.startsWith("image/")) {
      const reader = new FileReader();

      reader.onload = (e) => {
        const img = document.createElement("img");

        img.classList.add("prev-img");
        img.src = e.target.result;

        preview.appendChild(img);
      };

      reader.readAsDataURL(file);
    }
  }
};
