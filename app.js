// Mobile menu toggle
const menuBtn = document.getElementById('menuBtn');
const nav = document.getElementById('nav');
if (menuBtn && nav) {
  menuBtn.addEventListener('click', () => {
    const open = nav.classList.toggle('open');
    menuBtn.setAttribute('aria-expanded', open);
  });
}

// Character counter on the note form
const body = document.getElementById('body');
const count = document.getElementById('count');
if (body && count) {
  const update = () => { count.textContent = body.value.length + ' / 2000'; };
  body.addEventListener('input', update);
  update();
}

// Confirm before deleting a note
document.querySelectorAll('form.del').forEach(f => {
  f.addEventListener('submit', e => {
    if (!confirm('Delete this note?')) e.preventDefault();
  });
});
