let count = 5;

const addTag = () => {
  const container = document.getElementById("tags");
  const tagInput = document.createElement("input");

  tagInput.classList.add("tag");
  tagInput.type = "text";
  tagInput.name = `tags[${count}]`;

  if (count < 20) {
    container.appendChild(tagInput);
  }
  count++;
};
