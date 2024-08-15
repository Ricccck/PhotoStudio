const addCommas =(number)=>{
  return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

const priceEl = document.getElementById("price");
const originalNum = document.getElementById("price").innerText;
const formattedNum = addCommas(originalNum);

priceEl.innerText = formattedNum;