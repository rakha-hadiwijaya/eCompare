import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

const loaderStyles = document.createElement('style');
loaderStyles.textContent = `.page-loader{position:fixed;inset:0;z-index:2000;display:grid;place-items:center;background:rgba(255,250,240,.94);transition:opacity .2s,visibility .2s}.page-loader.is-hidden{opacity:0;visibility:hidden}.page-loader-card{width:min(340px,80vw);padding:1.5rem;display:flex;gap:1rem;align-items:center;background:#fffdf8;border:1px solid #e3d8be;border-radius:1rem;box-shadow:0 16px 40px #29252d20}.skeleton-logo{width:52px;height:52px;flex:none;border-radius:14px}.skeleton-lines{flex:1;display:grid;gap:.65rem}.skeleton-lines span{display:block;height:12px;border-radius:99px}.skeleton-lines span:last-child{width:68%}.skeleton-logo,.skeleton-lines span{background:linear-gradient(90deg,#e8dfcd 25%,#fffaf0 50%,#e8dfcd 75%);background-size:200% 100%;animation:skeleton 1.15s infinite}@keyframes skeleton{to{background-position:-200% 0}}.profile-avatar-placeholder{width:120px;height:120px;border-radius:50%;display:grid;place-items:center;background:linear-gradient(135deg,#dce5ff,#d7f2ef);color:#3158b8;font-size:3.5rem}@media(prefers-reduced-motion:reduce){.skeleton-logo,.skeleton-lines span{animation:none}}`;
document.head.append(loaderStyles);

document.body.insertAdjacentHTML('afterbegin', '<div class="page-loader" data-page-loader role="status" aria-label="Memuat halaman"><div class="page-loader-card"><div class="skeleton-logo"></div><div class="skeleton-lines"><span></span><span></span></div></div></div>');
const pageLoader = document.querySelector('[data-page-loader]');
const hidePageLoader = () => {
    if (!pageLoader) return;
    pageLoader.classList.add('is-hidden');
};

window.addEventListener('load', hidePageLoader);
window.addEventListener('pageshow', hidePageLoader);
document.addEventListener('click', (event) => {
    const link = event.target.closest('a[href]');
    if (!pageLoader || !link || link.target === '_blank' || event.ctrlKey || event.metaKey || event.shiftKey) return;

    const destination = new URL(link.href, window.location.href);
    if (destination.origin === window.location.origin && destination.href !== window.location.href && !link.hasAttribute('download')) {
        pageLoader.classList.remove('is-hidden');
    }
});
document.addEventListener('submit', (event) => {
    if (!event.defaultPrevented) pageLoader?.classList.remove('is-hidden');
});
