const params = new URLSearchParams(window.location.search);
const dayValue = params.get("day");

const tdElements = document.querySelectorAll("table td");

for (let i = 0; i < tdElements.length; i++) {
  const td = tdElements[i];
  const tdId = td.getAttribute("id");
  const encodedTdId = encodeURIComponent(tdId); // encode the ID value

  if (encodedTdId === dayValue) {
    td.style.backgroundColor = "#3DD6D0";
    td.style.outline = "2px solid #3DD6D0"; // add the green outline
    break;
  }
}

const thElements = document.querySelectorAll("table th");

for (let i = 0; i < thElements.length; i++) {
  const th = thElements[i];
  const thId = th.getAttribute("id");
  const encodedThId = encodeURIComponent(thId); // encode the ID value

  if (encodedThId === dayValue) {
    th.style.backgroundColor = "#3DD6D0";
    th.style.outline = "2px solid #3DD6D0"; // add the green outline
    break;
  }
}
