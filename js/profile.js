document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("profileForm");
  if (!form) return;

  const editBtn = document.getElementById("editBtn");
  const saveBtn = document.getElementById("saveBtn");
  const cancelBtn = document.getElementById("cancelBtn");

  let initialData = new FormData(form);

  function setReadOnly(readonly) {
    form.classList.toggle("readonly", readonly);
    editBtn.classList.toggle("hidden", !readonly);
    saveBtn.classList.toggle("hidden", readonly);
    cancelBtn.classList.toggle("hidden", readonly);
    // Also toggle disabled state to avoid accidental submits or clicks
    if (saveBtn) saveBtn.disabled = readonly;
    if (cancelBtn) cancelBtn.disabled = readonly;
  }

  // Prevent any default or bubbling that could submit/refresh the page
  editBtn?.addEventListener("click", (e) => {
    e.preventDefault();
    e.stopPropagation();
    initialData = new FormData(form);
    setReadOnly(false);
  });

  cancelBtn?.addEventListener("click", (e) => {
    e.preventDefault();
    e.stopPropagation();
    // Restore form values from snapshot
    for (const [key, value] of initialData.entries()) {
      const field = form.elements.namedItem(key);
      if (!field) continue;
      if (field instanceof RadioNodeList) {
        // not used here, but safeguard
      } else if (field.type === "file") {
        // cannot programmatically set file inputs; skip
      } else if (
        field.tagName === "TEXTAREA" ||
        field.tagName === "INPUT" ||
        field.tagName === "SELECT"
      ) {
        field.value = value;
      }
    }
    // Also clear file input if any
    const fileInput = form.querySelector('input[type="file"]');
    if (fileInput) fileInput.value = "";
    setReadOnly(true);
  });

  // Prevent accidental submits when still in read-only mode (e.g., pressing Enter)
  form.addEventListener("submit", (e) => {
    if (form.classList.contains("readonly")) {
      e.preventDefault();
    }
  });

  // Extra guard: prevent any bubbling submit on edit button clicks inside the form
  form.addEventListener("click", (e) => {
    const target = e.target;
    if (target && target instanceof HTMLElement && target.id === "editBtn") {
      e.preventDefault();
      e.stopPropagation();
    }
  });
  // Save submits the form (server validates and persists)
  saveBtn?.addEventListener("click", (e) => {
    // allow normal form submit
  });

  // Start in read-only state
  setReadOnly(true);
});
