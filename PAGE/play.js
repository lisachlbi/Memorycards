

let cartes = document.querySelectorAll(".flip-carte");

[...cartes].forEach((carte)=>{
  carte.addEventListener( 'click', function() {
  carte.classList.toggle('flipped');
  });
});