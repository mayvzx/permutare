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

document.querySelectorAll("[data-password-toggle]").forEach((button) => {
  const field = button.closest(".password-field");
  const input = field ? field.querySelector("[data-password-input]") : null;
  const label = button.querySelector("span");

  if (!input || !label) {
    return;
  }

  button.addEventListener("click", () => {
    const isVisible = input.type === "text";

    input.type = isVisible ? "password" : "text";
    button.setAttribute("aria-pressed", isVisible ? "false" : "true");
    button.setAttribute("aria-label", isVisible ? "Mostrar senha" : "Ocultar senha");
    label.textContent = isVisible ? "Ver" : "Ocultar";
    input.focus();
  });
});
