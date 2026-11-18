
window.addEventListener("load", () => {
    document.body.classList.remove("opacity-0", "translate-y-2");
});

window.addEventListener("pageshow", (event) => {
    if (event.persisted) {
        document.body.classList.remove("opacity-0", "translate-y-2");
    }
});

document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("a").forEach(link => {
        link.addEventListener("click", function (e) {
            const href = this.getAttribute("href");
            
            if (
                !href || 
                href.startsWith("#") || 
                href.startsWith("javascript:") || 
                this.target === "_blank" || 
                this.hasAttribute("download")
            ) {
                return;
            }

            e.preventDefault(); // Tahan perpindahan halaman sebentar
            document.body.classList.add("opacity-0", "translate-y-2"); // Jalankan animasi keluar

            setTimeout(() => {
                window.location.href = href; // Pindah halaman setelah 300ms
            }, 300);
        });
    });
});