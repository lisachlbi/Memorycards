let ajouter = document.getElementById("plus")
const modalWrapper = document.querySelector('.modal-wrapper')
let affichagePopup = document.querySelector(".affichage-popup")


ajouter.addEventListener("click", () => {
  modalWrapper.style.display = "block";
  affichagePopup.classList.add("show-popup")
  
})


let closeAffichage = () => {
  modalWrapper.style.display = "none";
}

modalWrapper.addEventListener("click", (e) => {
    if(e.target === modalWrapper) {
        closeAffichage()
    }
})


let themeSupp = document.querySelectorAll(".theme.supp")

let carteSupp = document.querySelectorAll(".carte.supp")

themeSupp.forEach((button) => {
  button.addEventListener("click", (e) => {
      let choix = confirm ("Voulez vous supprimer ?")
      if (choix) {
        id = e.target.dataset.id
        document.location.href = "../TRAITEMENT/themeSupp.php?id_theme="+id
      }
  })
})

carteSupp.forEach((button) => {
  button.addEventListener("click", (e) => {
      let choix = confirm ("Voulez vous supprimer ?")
      if (choix) {
        id_carte = e.target.dataset.idCarte
        id_theme = e.target.dataset.idTheme
        document.location.href = "../TRAITEMENT/carteSupp.php?id_carte="+id_carte+"&id_theme="+id_theme
      }
  })
})


