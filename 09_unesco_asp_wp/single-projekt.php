<?php
/**
 * The template for displaying all single posts and attachments.
 *
 * @package Hestia
 * @since Hestia 1.0
 */

get_header();
?>

<main id="main" class="site-main">

    <article>
        <div class="col_left">
            <img src="" alt="" class="billede" />
            <div>
                <p class="verdensmal"><strong>Verdensmål:</strong></p>
                <p class="uddannelsestrin">Udannelsestrin:</p>
                <p class="skolenavn">Skolenavn:</p>
                <p class="kontakt">Kontakt:</p>
            </div>
        </div>
        <div>
            <h2 class="projektnavn"></h2>
            <p class="kort_beskrivelse"></p>
            <p class="beskrivelse"></p>
        </div>
    </article>

</main><!-- #main -->
    
<style>

    article {
        margin-left: 5%;
        margin-right: 5%;
        margin-top: 10%;
    }

    @media only screen and (min-width: 480px) {
        article{
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
        }
    }

    .billede{
        max-width: 80%;
    }

    /*
    .col_left{
        display: flex;
        flex-flow: column;
        place-items: center;
    }
    */


</style>

<script>

    let projekt;

    // Her linker jeg til WPdb
    const dbUrl = "http://pletfolio.dk/kea/09_cms/unesco_wp/wp-json/wp/v2/projekt/" + <?php echo get_the_ID() ?>;
    console.log(dbUrl)

    // Async funktionen samt globale variabler defineres
    async function hentData(){
        console.log("hentData")
        const data = await fetch(dbUrl);
        projekt = await data.json();
        visProjekter();
    }

    // Her laver jeg funktionen for visProjekter(), som viser de enkelte projekter i singleview
    function visProjekter(){
    console.log("visProjekt")
        document.querySelector(".billede").src = projekt.billede.guid;
        document.querySelector(".projektnavn").textContent = projekt.projektnavn;
        document.querySelector(".kort_beskrivelse").textContent = projekt.kort_beskrivelse;
        document.querySelector(".beskrivelse").textContent = projekt.beskrivelse;
        document.querySelector(".verdensmal").textContent = "Fokus: " + projekt.verdensmal;
        document.querySelector(".uddannelsestrin").textContent = "Uddannelsestrin: " + projekt.uddannelsestrin;
        document.querySelector(".skolenavn").textContent = "Skole: " + projekt.skolenavn;
        document.querySelector(".kontakt").textContent = "Kontakt: " + projekt.kontakt;
    }



    hentData();

</script>

<?php
get_footer();
?>