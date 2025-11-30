<?php
session_start();
$is_admin = $_SESSION['is_admin'] ?? false;
if (!$is_admin) {
  header('Location: Login.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Painel Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/auth-admin.css">
  <style>
    .hidden { display: none !important; }
    .preview-img { max-width: 120px; max-height: 120px; margin-bottom: 10px; }
    #form-editar-container { 
      background-color: #f8f9fa; 
      padding: 20px; 
      border-radius: 8px; 
      border: 2px solid #dee2e6;
    }
  </style>
</head>
<body>
  <div class="container mt-5">
    <a href="Carrossel/index.php" class="btn btn-secondary mb-3 w-100">Voltar ao início</a>
    <div id="admin-panel">
      <h2 class="mb-4">Painel do Administrador</h2>
      <ul class="nav nav-tabs mb-3" id="adminTabs" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="atualizacoes-tab" data-bs-toggle="tab" data-bs-target="#atualizacoes" type="button" role="tab">Adicionar Atualização</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="jogo-tab" data-bs-toggle="tab" data-bs-target="#jogo" type="button" role="tab">Adicionar Jogo</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="editar-tab" data-bs-toggle="tab" data-bs-target="#editar" type="button" role="tab">Editar Jogo</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="carrossel-tab" data-bs-toggle="tab" data-bs-target="#carrossel" type="button" role="tab">Carrossel</button>
        </li>
      </ul>
      <div class="tab-content" id="adminTabsContent">
        <!-- Adicionar Atualização -->
        <div class="tab-pane fade show active" id="atualizacoes" role="tabpanel">
          <form id="form-atualizacao">
            <div class="mb-3">
              <label for="atualizacao-jogo" class="form-label">Selecionar Jogo</label>
              <select class="form-select" id="atualizacao-jogo" required>
                <option value="">Escolha um jogo...</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="atualizacao-nota" class="form-label">Nota de Atualização</label>
              <textarea class="form-control" id="atualizacao-nota" rows="4" placeholder="Descreva as novidades, correções, melhorias..." required></textarea>
            </div>
            <div class="mb-3">
              <label for="atualizacao-data" class="form-label">Data da Atualização</label>
              <input type="date" class="form-control" id="atualizacao-data" required>
            </div>
            <button type="submit" class="btn btn-success">Adicionar Atualização</button>
          </form>
          <div id="atualizacoes-list" class="mt-4">
            <h5>Atualizações Recentes</h5>
            <div id="atualizacoes-items"></div>
          </div>
        </div>
        <!-- Adicionar Jogo -->
        <div class="tab-pane fade" id="jogo" role="tabpanel">
          <form id="form-jogo">
            <div class="mb-3">
              <label for="jogo-img" class="form-label">Imagem</label>
              <input type="file" class="form-control" id="jogo-img" accept="image/*" required>
              <img id="preview-jogo" class="preview-img hidden" />
            </div>
            <div class="mb-3">
              <label for="jogo-nome" class="form-label">Nome do Jogo</label>
              <input type="text" class="form-control" id="jogo-nome" placeholder="Ex: Kingdom Hearts 3" required>
            </div>
            <div class="mb-3">
              <label for="jogo-desc" class="form-label">Descrição</label>
              <textarea class="form-control" id="jogo-desc" rows="3" placeholder="Descreva o jogo..." required></textarea>
            </div>
            <div class="mb-3">
              <label for="jogo-link-steam" class="form-label">Link da Steam (opcional)</label>
              <input type="url" class="form-control" id="jogo-link-steam" placeholder="https://store.steampowered.com/app/...">
              <small class="text-muted">Cole o link completo da página do jogo na Steam</small>
            </div>
            <div class="mb-3">
              <label class="form-label">Jogos Semelhantes (opcional - até 2)</label>
              <select class="form-select mb-2" id="jogo-semelhante-1">
                <option value="">Nenhum</option>
              </select>
              <select class="form-select" id="jogo-semelhante-2">
                <option value="">Nenhum</option>
              </select>
              <small class="text-muted">Selecione jogos relacionados para exibir como sugestões</small>
            </div>
            <button type="submit" class="btn btn-success">Adicionar Jogo</button>
          </form>
          <div id="jogos-list" class="mt-4"></div>
        </div>
        <!-- Editar Jogo -->
        <div class="tab-pane fade" id="editar" role="tabpanel">
          <h5 class="mb-3">Selecione um jogo para editar</h5>
          <div id="edit-jogos-list" class="mb-4"></div>
          
          <div id="form-editar-container" class="hidden">
            <hr class="my-4">
            <h5 class="mb-3">Editando Jogo: <span id="edit-jogo-title" class="text-primary"></span></h5>
            <form id="form-editar-jogo">
              <div class="mb-3">
                <label class="form-label">Nome do Jogo</label>
                <input type="text" class="form-control" id="edit-jogo-nome" disabled readonly style="background-color: #e9ecef; cursor: not-allowed;">
                <small class="text-muted">O nome do jogo não pode ser alterado.</small>
              </div>
              <div class="mb-3">
                <label for="edit-jogo-img" class="form-label">Nova Imagem (deixe em branco para manter a atual)</label>
                <input type="file" class="form-control" id="edit-jogo-img" accept="image/*">
                <div class="mt-2">
                  <small class="text-muted">Imagem atual:</small>
                  <img id="preview-edit-jogo" class="preview-img d-block mt-1" />
                </div>
              </div>
              <div class="mb-3">
                <label for="edit-jogo-desc" class="form-label">Descrição</label>
                <textarea class="form-control" id="edit-jogo-desc" rows="3" required></textarea>
              </div>
              <div class="mb-3">
                <label for="edit-jogo-link-steam" class="form-label">Link da Steam (opcional)</label>
                <input type="url" class="form-control" id="edit-jogo-link-steam" placeholder="https://store.steampowered.com/app/...">
                <small class="text-muted">Cole o link completo da página do jogo na Steam</small>
              </div>
              <button type="submit" class="btn btn-primary">Salvar Alterações</button>
              <button type="button" class="btn btn-secondary ms-2" id="cancel-edit">Cancelar</button>
              <button type="button" class="btn btn-danger ms-2" id="delete-jogo">Deletar Jogo</button>
            </form>
          </div>
        </div>
        <!-- Carrossel -->
        <div class="tab-pane fade" id="carrossel" role="tabpanel">
          <h5 class="mb-3">Gerenciar Jogos do Carrossel</h5>
          <p class="text-muted">Selecione até 4 jogos para exibir no carrossel da página inicial</p>
          
          <form id="form-carrossel">
            <div class="mb-3">
              <label for="carrossel-jogo-1" class="form-label">Jogo 1</label>
              <select class="form-select" id="carrossel-jogo-1">
                <option value="">Nenhum</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="carrossel-jogo-2" class="form-label">Jogo 2</label>
              <select class="form-select" id="carrossel-jogo-2">
                <option value="">Nenhum</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="carrossel-jogo-3" class="form-label">Jogo 3</label>
              <select class="form-select" id="carrossel-jogo-3">
                <option value="">Nenhum</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="carrossel-jogo-4" class="form-label">Jogo 4</label>
              <select class="form-select" id="carrossel-jogo-4">
                <option value="">Nenhum</option>
              </select>
            </div>
            <button type="submit" class="btn btn-success">Salvar Carrossel</button>
          </form>
          
          <div class="mt-4">
            <h5>Preview do Carrossel</h5>
            <div id="carrossel-preview" class="row gx-3">
              <p class="text-muted">Selecione os jogos acima para ver o preview</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    let jogos = [];
    let atualizacoes = [];
    let editIndex = null;

    // Carregar dados ao iniciar
    async function init() {
      await carregarJogos();
      await carregarAtualizacoes();
      document.getElementById('atualizacao-data').valueAsDate = new Date();
    }

    // ========== JOGOS ==========
    
    async function carregarJogos() {
      try {
        const response = await fetch('api/jogos.php');
        if (!response.ok) throw new Error('Erro ao carregar jogos');
        jogos = await response.json();
        populateGameDropdown();
        renderJogos();
        renderEditJogos();
      } catch (error) {
        alert('Erro ao carregar jogos: ' + error.message);
      }
    }



    // Preview de imagens
    document.getElementById('jogo-img').onchange = previewImage('preview-jogo');
    document.getElementById('edit-jogo-img').onchange = previewImage('preview-edit-jogo');

    function previewImage(previewId) {
      return function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById(previewId);
        if (file) {
          const reader = new FileReader();
          reader.onload = (ev) => {
            preview.src = ev.target.result;
            preview.classList.remove('hidden');
          };
          reader.readAsDataURL(file);
        } else {
          preview.classList.add('hidden');
        }
      };
    }

    // Preencher dropdown de jogos
    function populateGameDropdown() {
      const select = document.getElementById('atualizacao-jogo');
      select.innerHTML = '<option value="">Escolha um jogo...</option>';
      jogos.forEach(function(jogo, index) {
        const option = document.createElement('option');
        option.value = jogo.id; // CORRIGIDO: usar jogo.id em vez de index
        option.textContent = jogo.nome;
        select.appendChild(option);
      });
      
      // Preencher dropdowns de jogos semelhantes
      const semelhante1 = document.getElementById('jogo-semelhante-1');
      const semelhante2 = document.getElementById('jogo-semelhante-2');
      
      semelhante1.innerHTML = '<option value="">Nenhum</option>';
      semelhante2.innerHTML = '<option value="">Nenhum</option>';
      
      jogos.forEach(function(jogo) {
        const option1 = document.createElement('option');
        option1.value = jogo.id;
        option1.textContent = jogo.nome;
        semelhante1.appendChild(option1);
        
        const option2 = document.createElement('option');
        option2.value = jogo.id;
        option2.textContent = jogo.nome;
        semelhante2.appendChild(option2);
      });
      
      // Preencher dropdowns do carrossel
      for (let i = 1; i <= 4; i++) {
        const selectCarrossel = document.getElementById(`carrossel-jogo-${i}`);
        selectCarrossel.innerHTML = '<option value="">Nenhum</option>';
        jogos.forEach(function(jogo) {
          const option = document.createElement('option');
          option.value = jogo.id;
          option.textContent = jogo.nome;
          selectCarrossel.appendChild(option);
        });
      }
      
      // Carregar configuração atual do carrossel
      carregarCarrossel();
    }

    // Adicionar jogo
    document.getElementById('form-jogo').onsubmit = async function(e) {
      e.preventDefault();
      
      const nome = document.getElementById('jogo-nome').value;
      const desc = document.getElementById('jogo-desc').value;
      const linkSteam = document.getElementById('jogo-link-steam').value;
      const imgInput = document.getElementById('jogo-img');
      const semelhante1 = document.getElementById('jogo-semelhante-1').value;
      const semelhante2 = document.getElementById('jogo-semelhante-2').value;
      
      const file = imgInput.files[0];
      if (!file) {
        alert('Por favor, selecione uma imagem');
        return;
      }
      
      const reader = new FileReader();
      reader.onload = async function(ev) {
        try {
          const data = { 
            nome, 
            descricao: desc, 
            imagem: ev.target.result 
          };
          
          if (linkSteam) data.link_steam = linkSteam;
          if (semelhante1) data.jogo_semelhante_1 = parseInt(semelhante1);
          if (semelhante2) data.jogo_semelhante_2 = parseInt(semelhante2);
          
          const response = await fetch('api/jogos.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
          });
          
          if (!response.ok) throw new Error('Erro ao adicionar jogo');
          
          await carregarJogos();
          document.getElementById('form-jogo').reset();
          document.getElementById('preview-jogo').classList.add('hidden');
          alert('Jogo adicionado com sucesso!');
        } catch (error) {
          alert('Erro ao adicionar jogo: ' + error.message);
        }
      };
      reader.readAsDataURL(file);
    };
    
    function renderJogos() {
      const list = document.getElementById('jogos-list');
      list.innerHTML = '<h5 class="mt-4">Jogos Cadastrados</h5>';
      
      if (jogos.length === 0) {
        list.innerHTML += '<p class="text-muted">Nenhum jogo cadastrado ainda.</p>';
        return;
      }
      
      jogos.forEach(j => {
        const div = document.createElement('div');
        div.className = 'border rounded p-3 mb-3 bg-light';
        div.innerHTML = `
          <div class="row align-items-center">
            <div class="col-md-2">
              <img src="${j.imagem}" class="img-fluid rounded" style="max-height: 80px; object-fit: cover;" />
            </div>
            <div class="col-md-8">
              <h6 class="mb-1">${j.nome}</h6>
              <p class="mb-0 text-muted">${j.descricao}</p>
            </div>
            <div class="col-md-2 text-end">
              <button class="btn btn-sm btn-danger" onclick="deleteJogo(${j.id}, '${j.nome.replace(/'/g, "\\'")}')">Excluir</button>
            </div>
          </div>
        `;
        list.appendChild(div);
      });
    }

    // ========== ATUALIZAÇÕES ==========

    async function carregarAtualizacoes() {
      try {
        const response = await fetch('api/atualizacoes.php');
        if (!response.ok) throw new Error('Erro ao carregar atualizações');
        atualizacoes = await response.json();
        renderAtualizacoes();
      } catch (error) {
        alert('Erro ao carregar atualizações: ' + error.message);
      }
    }

    // Adicionar atualização
    document.getElementById('form-atualizacao').onsubmit = async function(e) {
      e.preventDefault();
      
      const jogoSelect = document.getElementById('atualizacao-jogo');
      const jogoId = jogoSelect.value;
      const nota = document.getElementById('atualizacao-nota').value.trim();
      const data = document.getElementById('atualizacao-data').value;
      
      if (!jogoId || jogoId === '' || jogoId === '0' || parseInt(jogoId) === 0) {
        alert('Por favor, selecione um jogo válido da lista');
        return;
      }
      
      if (!nota) {
        alert('Por favor, escreva a nota de atualização');
        return;
      }
      
      if (!data) {
        alert('Por favor, selecione a data');
        return;
      }
      
      try {
        const response = await fetch('api/atualizacoes.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ jogo_id: parseInt(jogoId), nota, data_atualizacao: data })
        });
        
        const result = await response.json();
        
        if (!response.ok) {
          // Mostrar detalhes completos do erro
          let errorMsg = result.error || 'Erro ao adicionar atualização';
          if (result.debug || result.jogo_id_procurado || result.jogos_disponiveis) {
            errorMsg += '\n\nDetalhes:';
            if (result.jogo_id_procurado !== undefined) {
              errorMsg += '\nID procurado: ' + result.jogo_id_procurado;
            }
            if (result.jogos_disponiveis) {
              errorMsg += '\nIDs disponíveis: ' + result.jogos_disponiveis.join(', ');
            }
            if (result.debug) {
              errorMsg += '\nDebug: ' + JSON.stringify(result.debug, null, 2);
            }
          }
          throw new Error(errorMsg);
        }
        
        await carregarAtualizacoes();
        this.reset();
        document.getElementById('atualizacao-data').valueAsDate = new Date();
        alert('Atualização adicionada com sucesso!');
      } catch (error) {
        alert('Erro: ' + error.message);
      }
    };
    
    function renderAtualizacoes() {
      const list = document.getElementById('atualizacoes-items');
      list.innerHTML = '';
      
      if (atualizacoes.length === 0) {
        list.innerHTML = '<p class="text-muted">Nenhuma atualização cadastrada ainda.</p>';
        return;
      }
      
      atualizacoes.forEach(a => {
        const div = document.createElement('div');
        div.className = 'border rounded p-3 mb-3 bg-light';
        const dataFormatada = new Date(a.data_atualizacao + 'T00:00:00').toLocaleDateString('pt-BR');
        div.innerHTML = `
          <div class="row">
            <div class="col-md-2">
              <img src="${a.jogo_imagem}" class="img-fluid rounded" style="max-height: 80px; object-fit: cover;" />
            </div>
            <div class="col-md-10">
              <h6 class="mb-1">${a.jogo_nome}</h6>
              <small class="text-muted">Data: ${dataFormatada}</small>
              <p class="mb-0 mt-2">${a.nota}</p>
              <button class="btn btn-sm btn-danger mt-2" onclick="removeAtualizacao(${a.id})">Remover</button>
            </div>
          </div>
        `;
        list.appendChild(div);
      });
    }
    
    window.removeAtualizacao = async function(id) {
      if (!confirm('Deseja remover esta atualização?')) return;
      
      try {
        const response = await fetch('api/atualizacoes.php', {
          method: 'DELETE',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ id })
        });
        
        if (!response.ok) throw new Error('Erro ao remover atualização');
        
        await carregarAtualizacoes();
        alert('Atualização removida com sucesso!');
      } catch (error) {
        alert('Erro ao remover atualização: ' + error.message);
      }
    };

    // Editar jogo
    function renderEditJogos() {
      const list = document.getElementById('edit-jogos-list');
      list.innerHTML = '';
      if (jogos.length === 0) {
        list.innerHTML = '<p class="text-muted">Nenhum jogo cadastrado ainda.</p>';
        return;
      }
      jogos.forEach(j => {
        const div = document.createElement('div');
        div.className = 'border rounded p-3 mb-2 bg-light';
        div.style.cursor = 'pointer';
        div.style.transition = 'all 0.2s';
        div.onmouseover = function() { this.style.backgroundColor = '#e9ecef'; };
        div.onmouseout = function() { this.style.backgroundColor = '#f8f9fa'; };
        div.innerHTML = `
          <div class="row align-items-center">
            <div class="col-auto">
              <img src="${j.imagem}" class="rounded" style="width: 60px; height: 60px; object-fit: cover;" />
            </div>
            <div class="col">
              <h6 class="mb-0">${j.nome}</h6>
              <small class="text-muted">${j.descricao.substring(0, 60)}${j.descricao.length > 60 ? '...' : ''}</small>
            </div>
            <div class="col-auto">
              <button class='btn btn-warning' onclick='editJogo(${j.id}); event.stopPropagation();'>Editar</button>
            </div>
          </div>
        `;
        div.onclick = function() { editJogo(j.id); };
        list.appendChild(div);
      });
    }
    
    window.editJogo = function(id) {
      const jogo = jogos.find(j => j.id === id);
      if (!jogo) return;
      
      editIndex = id;
      document.getElementById('form-editar-container').classList.remove('hidden');
      document.getElementById('edit-jogo-title').textContent = jogo.nome;
      document.getElementById('edit-jogo-nome').value = jogo.nome;
      document.getElementById('edit-jogo-desc').value = jogo.descricao;
      document.getElementById('edit-jogo-link-steam').value = jogo.link_steam || '';
      document.getElementById('preview-edit-jogo').src = jogo.imagem;
      document.getElementById('preview-edit-jogo').classList.remove('hidden');
      document.getElementById('form-editar-container').scrollIntoView({ behavior: 'smooth', block: 'start' });
    };
    
    document.getElementById('form-editar-jogo').onsubmit = async function(e) {
      e.preventDefault();
      if (editIndex === null) return;
      
      const desc = document.getElementById('edit-jogo-desc').value;
      const linkSteam = document.getElementById('edit-jogo-link-steam').value;
      const imgInput = document.getElementById('edit-jogo-img');
      
      const file = imgInput.files[0];
      
      if (file) {
        const reader = new FileReader();
        reader.onload = async function(ev) {
          try {
            const data = { id: editIndex, descricao: desc, imagem: ev.target.result };
            if (linkSteam) data.link_steam = linkSteam;
            
            const response = await fetch('api/jogos.php', {
              method: 'PUT',
              headers: { 'Content-Type': 'application/json' },
              body: JSON.stringify(data)
            });
            
            if (!response.ok) throw new Error('Erro ao atualizar jogo');
            
            await carregarJogos();
            document.getElementById('form-editar-container').classList.add('hidden');
            document.getElementById('form-editar-jogo').reset();
            editIndex = null;
            alert('Jogo atualizado com sucesso!');
          } catch (error) {
            alert('Erro ao atualizar jogo: ' + error.message);
          }
        };
        reader.readAsDataURL(file);
      } else {
        try {
          const data = { id: editIndex, descricao: desc };
          if (linkSteam) data.link_steam = linkSteam;
          
          const response = await fetch('api/jogos.php', {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
          });
          
          if (!response.ok) throw new Error('Erro ao atualizar jogo');
          
          await carregarJogos();
          document.getElementById('form-editar-container').classList.add('hidden');
          document.getElementById('form-editar-jogo').reset();
          editIndex = null;
          alert('Jogo atualizado com sucesso!');
        } catch (error) {
          alert('Erro ao atualizar jogo: ' + error.message);
        }
      }
    };
    
    document.getElementById('cancel-edit').onclick = function() {
      document.getElementById('form-editar-container').classList.add('hidden');
      document.getElementById('form-editar-jogo').reset();
      editIndex = null;
    };
    
    // Deletar jogo (botão dentro do formulário de edição)
    document.getElementById('delete-jogo').onclick = async function() {
      if (editIndex === null) return;
      
      const jogo = jogos.find(j => j.id === editIndex);
      if (!confirm(`Tem certeza que deseja deletar "${jogo.nome}"? Todas as atualizações relacionadas também serão removidas.`)) return;
      
      try {
        const response = await fetch('api/jogos.php', {
          method: 'DELETE',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ id: editIndex })
        });
        
        if (!response.ok) throw new Error('Erro ao deletar jogo');
        
        await carregarJogos();
        await carregarAtualizacoes();
        document.getElementById('form-editar-container').classList.add('hidden');
        document.getElementById('form-editar-jogo').reset();
        editIndex = null;
        alert('Jogo deletado com sucesso!');
      } catch (error) {
        alert('Erro ao deletar jogo: ' + error.message);
      }
    };
    
    // Deletar jogo (botão na listagem)
    window.deleteJogo = async function(id, nome) {
      if (!confirm(`Tem certeza que deseja deletar "${nome}"? Todas as atualizações relacionadas também serão removidas.`)) return;
      
      try {
        const response = await fetch('api/jogos.php', {
          method: 'DELETE',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ id })
        });
        
        if (!response.ok) throw new Error('Erro ao deletar jogo');
        
        await carregarJogos();
        await carregarAtualizacoes();
        alert('Jogo deletado com sucesso!');
      } catch (error) {
        alert('Erro ao deletar jogo: ' + error.message);
      }
    };
    
    // ========== CARROSSEL ==========
    
    async function carregarCarrossel() {
      try {
        const response = await fetch('api/carrossel.php');
        if (!response.ok) throw new Error('Erro ao carregar carrossel');
        const data = await response.json();
        
        // Preencher os selects
        for (let i = 1; i <= 4; i++) {
          const select = document.getElementById(`carrossel-jogo-${i}`);
          if (data[`jogo_${i}`]) {
            select.value = data[`jogo_${i}`];
          }
        }
        
        renderCarrosselPreview();
      } catch (error) {
        console.error('Erro ao carregar carrossel:', error);
      }
    }
    
    function renderCarrosselPreview() {
      const preview = document.getElementById('carrossel-preview');
      preview.innerHTML = '';
      
      let hasGames = false;
      for (let i = 1; i <= 4; i++) {
        const selectId = `carrossel-jogo-${i}`;
        const jogoId = document.getElementById(selectId).value;
        
        if (jogoId) {
          hasGames = true;
          const jogo = jogos.find(j => j.id == jogoId);
          if (jogo) {
            const col = document.createElement('div');
            col.className = 'col-md-3 mb-3';
            col.innerHTML = `
              <div class="card">
                <img src="${jogo.imagem}" class="card-img-top" style="height: 150px; object-fit: cover;">
                <div class="card-body p-2">
                  <small class="fw-bold">${jogo.nome}</small>
                </div>
              </div>
            `;
            preview.appendChild(col);
          }
        }
      }
      
      if (!hasGames) {
        preview.innerHTML = '<p class="text-muted">Selecione os jogos acima para ver o preview</p>';
      }
    }
    
    // Atualizar preview quando mudar seleção
    for (let i = 1; i <= 4; i++) {
      document.getElementById(`carrossel-jogo-${i}`).onchange = renderCarrosselPreview;
    }
    
    document.getElementById('form-carrossel').onsubmit = async function(e) {
      e.preventDefault();
      
      const data = {};
      for (let i = 1; i <= 4; i++) {
        const jogoId = document.getElementById(`carrossel-jogo-${i}`).value;
        if (jogoId) {
          data[`jogo_${i}`] = parseInt(jogoId);
        }
      }
      
      try {
        const response = await fetch('api/carrossel.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(data)
        });
        
        if (!response.ok) throw new Error('Erro ao salvar carrossel');
        
        alert('Carrossel atualizado com sucesso!');
      } catch (error) {
        alert('Erro ao salvar carrossel: ' + error.message);
      }
    };
    
    // Inicializar
    init();
  </script>
</body>
</html>
