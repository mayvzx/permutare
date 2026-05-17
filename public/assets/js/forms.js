document.querySelectorAll("[data-preview-input]").forEach((input) => {
  const preview = document.querySelector("[data-preview]");

  input.addEventListener("change", () => {
    const file = input.files && input.files[0];
    if (!file || !preview) {
      return;
    }

    preview.src = URL.createObjectURL(file);
    preview.classList.add("is-visible");
  });
});

document.querySelectorAll("textarea[maxlength]").forEach((textarea) => {
  const counter = document.createElement("small");
  counter.className = "muted";
  textarea.insertAdjacentElement("afterend", counter);

  const update = () => {
    counter.textContent = `${textarea.value.length}/${textarea.maxLength}`;
  };

  textarea.addEventListener("input", update);
  update();
});
