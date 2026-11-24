// Memperbarui ikon Lucide setelah struktur halaman tersedia.
const refreshIcons = () => window.lucide?.createIcons();

// Menghubungkan kontrol sidebar, notifikasi, tema, dialog, menu kartu, dan grafik.
document.addEventListener('DOMContentLoaded', () => {
	refreshIcons();

	// Membuka atau menutup navigasi samping melalui tombol dan overlay.
	const sidebar = document.querySelector('[data-sidebar]');
	const overlay = document.querySelector('[data-sidebar-overlay]');
	document.querySelector('[data-sidebar-toggle]')?.addEventListener('click', () => sidebar?.classList.toggle('open'));
	overlay?.addEventListener('click', () => sidebar?.classList.remove('open'));

	// Mengelola pembukaan satu panel notifikasi dan menutupnya saat klik di luar.
	document.querySelectorAll('.admin-notification-toggle').forEach((toggle) => {
		const wrap = toggle.closest('.admin-notification-wrap');
		const panel = wrap?.querySelector('[data-notification-panel]');
		toggle.addEventListener('click', (event) => {
			event.stopPropagation();
			const isOpen = wrap?.classList.contains('open');
			document.querySelectorAll('.admin-notification-wrap').forEach((item) => {
				const itemPanel = item.querySelector('[data-notification-panel]');
				item.classList.remove('open');
				if (itemPanel) {
					itemPanel.hidden = true;
					itemPanel.style.display = 'none';
				}
			});
			if (!isOpen && wrap && panel) {
				wrap.classList.add('open');
				panel.hidden = false;
				panel.style.display = 'block';
			}
		});
	});
	// Menutup panel notifikasi yang tidak sedang menjadi target klik.
	document.addEventListener('click', (event) => {
		const target = event.target;
		document.querySelectorAll('.admin-notification-wrap').forEach((wrap) => {
			const panel = wrap.querySelector('[data-notification-panel]');
			if (!wrap.contains(target) && panel) {
				wrap.classList.remove('open');
				panel.hidden = true;
				panel.style.display = 'none';
			}
		});
	});

	// Memulihkan pilihan tema dan menyimpan perubahan tema ke penyimpanan lokal.
	const storedTheme = localStorage.getItem('parking-point-theme');
	if (storedTheme === 'dark') document.documentElement.classList.add('dark');
	document.querySelector('[data-theme-toggle]')?.addEventListener('click', () => {
		document.documentElement.classList.toggle('dark');
		localStorage.setItem('parking-point-theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
	});

	// Meminta konfirmasi sebelum formulir logout benar-benar dikirim.
	const modal = document.querySelector('[data-logout-modal]');
	const form = document.querySelector('[data-logout-form]');
	document.querySelector('[data-logout-form] button')?.addEventListener('click', (event) => {
		event.preventDefault();
		modal?.classList.add('open');
		modal?.setAttribute('aria-hidden', 'false');
	});
	document.querySelector('[data-modal-close]')?.addEventListener('click', () => modal?.classList.remove('open'));
	document.querySelector('[data-logout-confirm]')?.addEventListener('click', () => form?.submit());

	// Membuka satu menu kartu dan menutup menu lain saat menu diklik.
	document.querySelectorAll('[data-menu-toggle]').forEach((toggle) => {
		toggle.addEventListener('click', (event) => {
			event.stopPropagation();
			const menu = toggle.closest('[data-card-menu]');
			document.querySelectorAll('[data-card-menu].open').forEach((item) => {
				if (item !== menu) item.classList.remove('open');
			});
			menu?.classList.toggle('open');
		});
	});
	// Menutup seluruh menu kartu ketika area halaman diklik.
	document.addEventListener('click', () => {
		document.querySelectorAll('[data-card-menu].open').forEach((menu) => menu.classList.remove('open'));
	});

	// Membentuk grafik SVG dari seri data yang disediakan elemen Blade.
	document.querySelectorAll('[data-chart]').forEach((chart) => {
		const masuk = JSON.parse(chart.dataset.masuk || '[]');
		const keluar = JSON.parse(chart.dataset.keluar || '[]');
		const labels = JSON.parse(chart.dataset.labels || '[]');
		const values = [...masuk, ...keluar].map(Number);
		const max = Math.max(...values, 1);
		const width = 720;
		const height = 210;
		const padding = { top: 12, right: 12, bottom: 28, left: 12 };
		const plotWidth = width - padding.left - padding.right;
		const plotHeight = height - padding.top - padding.bottom;
		const points = (series) => series.map((value, index) => `${padding.left + (index * plotWidth / Math.max(series.length - 1, 1))},${padding.top + plotHeight - (Number(value) / max * plotHeight)}`).join(' ');
		const guideLines = [0, .5, 1].map((ratio) => `<line class="admin-chart-grid" x1="${padding.left}" x2="${width - padding.right}" y1="${padding.top + plotHeight * ratio}" y2="${padding.top + plotHeight * ratio}" />`).join('');
		const labelNodes = labels.map((label, index) => `<text class="admin-chart-label" text-anchor="middle" x="${padding.left + (index * plotWidth / Math.max(labels.length - 1, 1))}" y="${height - 5}">${label}</text>`).join('');
		chart.innerHTML = `<svg viewBox="0 0 ${width} ${height}" role="img" aria-label="Grafik kendaraan masuk dan keluar">${guideLines}<polyline class="admin-chart-line" points="${points(masuk)}" /><polyline class="admin-chart-line out" points="${points(keluar)}" />${labelNodes}</svg>`;
	});
});
