var tx = document.getElementsByTagName('textarea');//РАСТЯГИВАЕМ_textarea

for (var i = 0; i < tx.length; i++) {

tx[i].setAttribute('style', 'height:' + (tx[i].scrollHeight));

tx[i].addEventListener("input", OnInput, false);

}

function OnInput() {

this.style.height = 'auto';

this.style.height = (this.scrollHeight) + 'px';//////console.log(this.scrollHeight);

}