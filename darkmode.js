document.addEventListener('DOMContentLoaded', function() {
    const btn = document.getElementById('toggleTheme');
    const saved = localStorage.getItem('theme');
    if (saved === 'dark') {
        document.body.classList.add('dark-mode');
    }
    if (btn) {
        btn.addEventListener('click', function() {
            document.body.classList.toggle('dark-mode');
            localStorage.setItem('theme', document.body.classList.contains('dark-mode') ? 'dark' : 'light');
        });
    }
});
