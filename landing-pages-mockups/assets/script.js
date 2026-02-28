(function () {
  /* Avoids duplicated execution */
  if (window.__LPV2_INIT__) return;
  window.__LPV2_INIT__ = true;

  /* Optional: route restriction ----------------------------------------- */
  if (!location.pathname.replace(/\/+$/,'').endsWith('/website-services'))
    console.warn('showcase-v2 ativo em rota diferente.');

  /* Data ------------------------------------------------------------- */
  const cases = [
    { id:1, full:'assets/mediamaratonsantamarta.png' },
    { id:2, full:'assets/middlewaymarketing.png' },
    { id:3, full:'assets/tiendaonceonce-onrender.png' },
    { id:4, full:'assets/unitivecounseling.png' }
  ];

  /* DOM --------------------------------------------------------------- */
  const grid = document.getElementById('cardGrid-lpv2');
  if (!grid){
    console.error('ID "cardGrid-lpv2" não encontrado.');
    return;
  }
  if (grid.children.length) return;   /* já renderizado? sai. */

  /* Cards --------------------------------------------------------- */
  const createCard = (c) => {
    const el = document.createElement('div');
    el.className = 'modal-card-st bg-transparent h-96';   /* altura 24 rem */
    el.innerHTML = `
      <div class="mac-dots">
        <span class="dot-red-v2"></span>
        <span class="dot-yellow-v2"></span>
        <span class="dot-green-v2"></span>
      </div>
      <div class="image-scroll-lpv2">
        <img src="${c.full}" alt="Layout ${c.id}" loading="lazy">
      </div>`;
    return el;
  };

  /* Grid rendering --------------------------------------------------- */
  cases.forEach(c => grid.appendChild(createCard(c)));

})();