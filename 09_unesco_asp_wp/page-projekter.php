<?php
/**
 * The template for displaying all single posts and attachments.
 *
 * @package Hestia
 * @since Hestia 1.0
 */

get_header();
?>

<h1 class="entry-title">Søg verdensmålprojekter</h1>

<nav id="filtrering"></nav>

<section id="popup">
    <div id="luk">&#x2715 </div>
    <article>
        <img src="" alt="" class="billede" />
        <div>
            <p class="verdensmal">Verdensmål:</p>
            <p class="uddannelsestrin">Udannelsestrin:</p>
            <p class="skolenavn">Skolenavn:</p>
            <p class="kontakt">Kontakt:</p>
        </div>
        <h2 class="projektnavn"></h2>
        <p class="kort_beskrivelse"></p>
        <p class="beskrivelse"></p>
    </article>
</section>

<template class="loopview">
    <article>
        <div class="img_box">
            <img src="" alt="" class="billede" />
        </div>
        <h2 class="projektnavn"></h2>
        <p class="kort_beskrivelse"></p>
        <p class="verdensmal"></p>
    </article>
</template>

<main id="main" class="site-main"></main><!-- #main -->

		
<style>

    h1 {
        margin-top: 100px;
        font-size: 2rem;
        text-align: center;
        text-transform: uppercase;
        font-weight: bold;
        color: #3D8AA5;
    }

    @media only screen and (min-width: 480px) {
        h1 {
            margin-top: 10%;
            font-size: 4rem;
            text-align: center;
            text-transform: uppercase;
            font-weight: bold;
            color: #3D8AA5;
        }
    }

    .page .page-title {
        margin-top: 45px;
        color: #222;
        font-size: 26px;
        font-size: 1.625rem;
        font-weight: 700;
        text-align: center;
        text-transform: uppercase;
    }

    #filtrering {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        gap: 10px;
        margin: 0 auto;
        max-width: 85vw;
        margin-bottom: 10px;
        align-content: center;
    }


    .projektnavn {
        color: #0bb4aa;
    }

    .kort_beskrivelse,
    .verdensmal {
        color: #777;
    }

    article {
        padding: 10px;
        cursor: pointer;
        place-content: center;
        background-color: #f5f5f5;
    }

    /* article:hover {
        box-shadow: 5px 5px #147ca6;
    } */

    main {
        max-width: 1000px;
        /* margin: auto 20px auto 20px; */
    }

    main {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 5px;
        margin: 0 auto;
    }

    #popup {
        display: none;
        position: fixed;
        left: 0;
        top: 0;
        width: 100vw;
        background-color: rgba(0, 0, 0, 0.8);
        overflow: auto;
    }

    #popup article {
        width: 70vw;
        height: 500px;
        margin: 12rem auto;
        border-radius: 25px;
        padding: 12px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        grid-template-rows: 200px 100px;
        gap: 20px;
    }

    #popup article h2 {
        margin: 10px 0px 70px 0px;
        grid-column: 2/3;
        grid-row: 1/2;
        place-self: start;
        /* color: #f2f2f2; */
    }

    #popup article img {
        border-radius: 8px;
        place-self: center;
        grid-column: 1/2;
        grid-row: 1/3;
        max-height: 100%;
        max-width: 50%;
    }

    #luk {
        position: fixed;
        padding: 6.25em 0 0 7.5em;
        font-size: 2em;
        font-weight: bolder;
        color: black;
        cursor: pointer;
    }

    #luk:hover {
        color: #032f40;
    }

    .beskrivelse{
        font-size: 0.8rem;
    }

    .img_box{
        padding: 0;
    }

</style>

<script>
    "use strict";
	// Tjekker om DOM'en er loaded før siden vises
    window.addEventListener("DOMContentLoaded", start);
    function start() {
    console.log("start");

    // Definerer stien til json-array projekter + kategorier i WordpressDB:
    // Henter 2 collections fra samme database
    const dbUrl = "http://pletfolio.dk/kea/09_cms/unesco_wp/wp-json/wp/v2/projekt?per_page=100";
    const vmUrl = "http://pletfolio.dk/kea/09_cms/unesco_wp/wp-json/wp/v2/verdensml?per_page=100";
    console.log(vmUrl);

    // definere globale variable
    const main = document.querySelector("main");
    const template = document.querySelector(".loopview").content;
    const popup = document.querySelector("#popup");
    const article = document.querySelector("article");
    const lukKnap = document.querySelector("#luk");
    const header = document.querySelector("h1");

    let projekter;
    let filter = "alle";
    let verdensml;
    let filterProjekt= "alle"

    // Henter json-data fra WordpressSB via fetch() fra to forskellige collections i samme database
    async function hentData() {
        const data = await fetch(dbUrl);
        const vmdata = await fetch(vmUrl);
        projekter = await data.json();
        verdensml = await vmdata.json();
        console.log("Projekter", projekter);
        console.log("Verdensmål", verdensml);
        visProjekter();
        opretKnapper();
    }
    
    // Her opretter jeg funktionen knapper samt knapperne selv, som jeg giver et indlejret billede
    function opretKnapper(){
        verdensml.forEach(vm =>{
            document.querySelector("#filtrering").innerHTML += `<button class="filter" data-projekt="${vm.id}"><img src="${vm.verdensbillede.guid}" alt="" class="billede" /></button>`
        })
        addEventListenersToButtons();
    }

    // Her tjekker jeg, om der bliver clicket på knapperne, samt definerer, hvad der skal ske derefter
    function addEventListenersToButtons(){
        document.querySelectorAll("#filtrering button").forEach(elm =>{
            elm.addEventListener("click", filtrering);
        })

    };

    function filtrering(){
        filterProjekt = this.dataset.projekt;

        console.log(filterProjekt);
        visProjekter();

    }

    // loop'er gennem alle projekterne i json-arrayet
    function visProjekter() {
        console.log("visProjekter");
        main.textContent = ""; // Her resetter jeg DOM'en ved at tilføje en tom string
        // for hver projekt i arrayet, skal der tjekkes om de opfylder filter-kravet og derefter vises i DOM'en.
        projekter.forEach((projekt) => {
            if (filterProjekt == "alle" || projekt.verdensml.includes(parseInt(filterProjekt))) {
                const klon = template.cloneNode(true);
                klon.querySelector(".billede").src = projekt.billede.guid;
                klon.querySelector(".projektnavn").textContent = projekt.projektnavn;
                //klon.querySelector(".skolenavn").textContent = projekt.skolenavn;
                klon.querySelector(".kort_beskrivelse").textContent = projekt.kort_beskrivelse;
                // tilføjer eventlistner til hvert article-element og lytter efter klik på artiklerne. Funktionen "visDetaljer" bliver kaldt ved klik.
                klon.querySelector("article")
                .addEventListener("click", () => {location.href = projekt.link});
                // tilføjer klon-template-elementet til main-elementet (så det hele vises i DOM'en)
                main.appendChild(klon);
            }
        })
    }

    // tilføjer objekter fra arrayet (for hver projekt) til popup-vindue. Samt sætter cursor til default, så man ikke tror man kan klikke på elementet igen.
    function visDetaljer(projekt) {
        console.log(projekt);
        article.style.cursor = "default";
        popup.style.display = "block";
        popup.querySelector(".billede").src = projekt.billede.guid;
        popup.querySelector(".billede").style.maxWidth = "50%";
        popup.querySelector(".projektnavn").textContent = projekt.projektnavn.rendered;
        popup.querySelector(".kort_beskrivelse").textContent = projekt.kort_beskrivelse;
        popup.querySelector(".beskrivelse").textContent = projekt.beskrivelse;
    }

    // ved klik på luk-knappen forsvinder popup-vindue
    lukKnap.addEventListener("click", () => (popup.style.display = "none"));
    lukKnap.addEventListener(
        "click",
        () => (document.querySelector(".nav").style.position = "sticky")
    );
        hentData();
    }

</script>
<?php
get_footer();
?>