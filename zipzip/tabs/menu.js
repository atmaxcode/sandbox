let menus = [];

const menuContainer = document.getElementById('menuContainer');
const menuList = document.getElementById('menuList');
let menuModal;

async function loadMenus() {
  try {
    const res = await fetch('menus.json');
    menus = await res.json();
    renderMenus();
  } catch (err) {
    console.error('Gagal memuat menus.json:', err);
  }
}

function renderMenus() {
  menuContainer.innerHTML = '';
  menus.forEach(menu => {
    const li = document.createElement('li');
    li.className = 'nav-item mx-2';
    li.innerHTML = `
      <a class="nav-link text-white" href="#" onclick="loadPage('${menu.url}')">
        <i class="uil ${menu.icon}"></i> ${menu.name}
      </a>`;
    menuContainer.appendChild(li);
  });
}

function renderMenuList() {
  menuList.innerHTML = '';
  menus.forEach((menu, index) => {
    const div = document.createElement('div');
    div.className = 'menu-item';
    div.innerHTML = `
      <span><i class="uil ${menu.icon}"></i> ${menu.name}</span>
      <div>
        <span class="edit-btn" data-index="${index}">✏️</span>
        <span class="delete-btn" onclick="deleteMenu(${index})">🗑️</span>
      </div>
    `;
    menuList.appendChild(div);
  });

  // Tambahkan event listener ke setiap edit-btn
  document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const index = btn.getAttribute('data-index');
      fillForm(index);
      menuModal.show();
    });
  });
}

function loadPage(url) {
  document.getElementById('iframe').src = url;
}

function openModal() {
  if (!menuModal) {
    menuModal = new bootstrap.Modal(document.getElementById('menuModal'));
  }
  menuModal.show();
  renderMenuList();
}

function fillForm(index) {
  const menu = menus[index];
  document.getElementById('menuName').value = menu.name;
  document.getElementById('menuUrl').value = menu.url;
  document.getElementById('menuIcon').value = menu.icon;
  document.getElementById('editIndex').value = index;
}

function saveMenu() {
  const name = document.getElementById('menuName').value.trim();
  const url = document.getElementById('menuUrl').value.trim();
  const icon = document.getElementById('menuIcon').value.trim();
  const index = document.getElementById('editIndex').value;

  if (!name || !url || !icon) return alert('Isi semua kolom');

  if (index === '') {
    menus.push({ name, url, icon });
  } else {
    menus[index] = { name, url, icon };
  }

  fetch('save_menu.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(menus)
  }).then(() => {
    renderMenus();
    renderMenuList();
    clearForm();
  });
}

function deleteMenu(index) {
  if (!confirm("Yakin ingin menghapus menu ini?")) return;

  menus.splice(index, 1);
  fetch('save_menu.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(menus)
  }).then(() => {
    renderMenus();
    renderMenuList();
  });
}

function clearForm() {
  document.getElementById('menuName').value = '';
  document.getElementById('menuUrl').value = '';
  document.getElementById('menuIcon').value = '';
  document.getElementById('editIndex').value = '';
}

loadMenus();
