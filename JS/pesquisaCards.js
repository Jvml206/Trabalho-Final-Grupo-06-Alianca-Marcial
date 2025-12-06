const inputPesquisa = document.getElementById('pesquisaInput');
const cards = document.querySelectorAll(".card-pesq");
inputPesquisa.addEventListener('keyup', () => {
    const termo = inputPesquisa.value.toLowerCase().trim();
    
    cards.forEach(card =>{
        const textoDados = card.querySelectorAll('p');
        let valoresPesq = '';
        
        textoDados.forEach(v => valoresPesq += v.innerText.toLocaleLowerCase()+' ');
        
        card.style.display = valoresPesq.includes(termo) ? '' : 'none';
    });
});