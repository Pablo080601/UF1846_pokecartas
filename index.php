<?php


function getPokemonData()
{
    $pokemons = [];
    for ($i = 0; $i < 5; $i++) {
        // 1) genera número aleatorio
        $pokemon_id = rand(1, 151);
        // 2) lee el contenido de la api 
        $pokemon_elegido = file_get_contents("https://pokeapi.co/api/v2/pokemon/$pokemon_id/");
        // 3) lo decodifica
        $pokemon_data = json_decode($pokemon_elegido, true);
        // 4) Creo un objeto pokemon (me quedo sólo con los datos que necesito):

        $tipos = [];
        foreach ($pokemon_data["types"] as $key => $type) {
            array_push($tipos, $type["type"]["name"]);
        }
        $habilidades = [];
        foreach ($pokemon_data["abilities"] as $key => $ability) {
            array_push($habilidades, $ability["ability"]["name"]);
        }

        $pokemon = [
            // nombre (name)
            "nombre" => $pokemon_data["name"],
            // imagen (sprites[front_default])
            "sprites" => $pokemon_data["sprites"],
            // tipos (types[]-> dentro de cada elemento [type][name])
            "tipos" => $tipos,
            "habilidades" => $habilidades

        ];
        array_push($pokemons, $pokemon);
    }

    return $pokemons;
}

$pokemon = getPokemonData();


function renderCards($pokeArray)
{
    // recibe datos y genera el html
    echo "<section id='pokecartas' class='contenedor'>";
    foreach ($pokeArray as $key => $pokemon) {
        $shiny = itsShiny();
        $classShiny = $shiny ? 'shiny' : '';
        $img = $shiny ? 'front_shiny' : 'front_default';

        echo "<div class='carta " . $classShiny . " " . $pokemon["tipos"][0] . "'>";
        echo "<div class='img-container'>";
        echo "<img src='" . $pokemon['sprites'][$img] . "' alt='$pokemon[nombre]'>";
        echo "</div>";
        echo "<div class='datos'>";
        echo "<h3>$pokemon[nombre]</h3>";
        echo '<div class="tipos-pokemon">';
        foreach ($pokemon["tipos"] as $key => $tipo) {
            echo "<span>$tipo</span>";
        }
        echo '</div>';
        echo '<ul class="habilidades">';
        foreach ($pokemon["habilidades"] as $key => $habilidad) {
            echo "<li>$habilidad</li>";
        }
        echo "</ul>";
        echo "</div>";
        echo "</div>";
    }
    echo "</section>";
}

function itsShiny()
{
    $probabilidad = rand(1, 20);
    if ($probabilidad === 1) {
        return true;
    } else {
        return false;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PokeWeb</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1>PokeCartas</h1>
    <!--     <section id="pokecartas">
        <div class="carta">
            <div class="img-container">
                <img src="https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/25.png" alt="pikachu">
            </div>
            <div class="contenedor">
                <div class="datos">
                    <h3>Pikachu</h3>
                    <div class="tipos-pokemon">
                        <span>eléctrico</span>
                        <span>otro más</span>
                    </div>
                    <ul class="habilidades">
                        <li>impactrueno</li>
                        <li>chispitas</li>
                    </ul>
                </div>
            </div>
        </div>
    </section> -->
    <?php renderCards($pokemon)
    ?>
    <a href="index.php">Nuevas cartas</a>
</body>
</html>