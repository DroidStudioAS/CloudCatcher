<?php

namespace Database\Seeders;

use App\Models\CityModel;
use Faker\Factory;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function  run()
    {
        //all cities and countries in europe


        $european_countries = array(
            "Albania" => array("Tirana", "Durrës", "Vlorë", "Shkodër", "Fier", "Korçë", "Elbasan", "Berat", "Lushnjë", "Pogradec"),
            "Andorra" => array("Andorra la Vella", "Escaldes-Engordany", "Encamp", "Sant Julià de Lòria", "La Massana", "Canillo", "Ordino", "El Pas de la Casa", "Arinsal", "Escàs"),
            "Austria" => array("Vienna", "Graz", "Linz", "Salzburg", "Innsbruck", "Klagenfurt", "Villach", "Wels", "Sankt Pölten", "Dornbirn"),
            "Belarus" => array("Minsk", "Gomel", "Mogilev", "Vitebsk", "Hrodna", "Brest", "Babruysk", "Baranavichy", "Pinsk", "Orsha"),
            "Belgium" => array("Brussels", "Antwerp", "Ghent", "Charleroi", "Liège", "Bruges", "Namur", "Leuven", "Mons", "Aalst"),
            "Bosnia and Herzegovina" => array("Sarajevo", "Banja Luka", "Tuzla", "Zenica", "Mostar", "Prijedor", "Brčko", "Bihać", "Bugojno", "Trebinje"),
            "Bulgaria" => array("Sofia", "Plovdiv", "Varna", "Burgas", "Ruse", "Stara Zagora", "Pleven", "Sliven", "Dobrich", "Shumen"),
            "Croatia" => array("Zagreb", "Split", "Rijeka", "Osijek", "Zadar", "Slavonski Brod", "Pula", "Sesvete", "Karlovac", "Varaždin"),
            "Cyprus" => array("Nicosia", "Limassol", "Larnaca", "Famagusta", "Paphos", "Kyrenia", "Protaras", "Morphou", "Aradippou", "Paralimni"),
            "Czech Republic" => array("Prague", "Brno", "Ostrava", "Plzeň", "Liberec", "Olomouc", "Ústí nad Labem", "Hradec Králové", "Pardubice", "Zlín"),
            "Denmark" => array("Copenhagen", "Aarhus", "Odense", "Aalborg", "Esbjerg", "Randers", "Kolding", "Vejle", "Horsens", "Roskilde"),
            "Estonia" => array("Tallinn", "Tartu", "Narva", "Pärnu", "Kohtla-Järve", "Viljandi", "Rakvere", "Maardu", "Sillamäe", "Kuressaare"),
            "Finland" => array("Helsinki", "Espoo", "Tampere", "Vantaa", "Oulu", "Turku", "Jyväskylä", "Lahti", "Kuopio", "Kouvola"),
            "France" => array("Paris", "Marseille", "Lyon", "Toulouse", "Nice", "Nantes", "Strasbourg", "Montpellier", "Bordeaux", "Lille"),
            "Germany" => array("Berlin", "Hamburg", "Munich", "Cologne", "Frankfurt", "Stuttgart", "Düsseldorf", "Dortmund", "Essen", "Leipzig"),
            "Greece" => array("Athens", "Thessaloniki", "Patras", "Heraklion", "Larissa", "Volos", "Ioannina", "Chania", "Chalcis", "Kavala"),
            "Hungary" => array("Budapest", "Debrecen", "Szeged", "Miskolc", "Pécs", "Győr", "Nyíregyháza", "Kecskemét", "Székesfehérvár", "Szombathely"),
            "Iceland" => array("Reykjavík", "Kópavogur", "Hafnarfjörður", "Akureyri", "Reykjanesbær", "Garðabær", "Mosfellsbær", "Árborg", "Akranes", "Fjarðabyggð"),
            "Ireland" => array("Dublin", "Cork", "Limerick", "Galway", "Waterford", "Drogheda", "Swords", "Dundalk", "Bray", "Navan"),
            "Italy" => array("Rome", "Milan", "Naples", "Turin", "Palermo", "Genoa", "Bologna", "Florence", "Bari", "Catania"),
            "Kazakhstan" => array("Almaty", "Nur-Sultan", "Shymkent", "Karaganda", "Aktobe", "Taraz", "Pavlodar", "Ust-Kamenogorsk", "Uralsk", "Kostanay"),
            "Latvia" => array("Riga", "Daugavpils", "Liepāja", "Jelgava", "Jūrmala", "Ventspils", "Rēzekne", "Valmiera", "Ogre", "Tukums"),
            "Liechtenstein" => array("Vaduz", "Schaan", "Triesen", "Balzers", "Eschen", "Mauren", "Triesenberg", "Ruggell", "Gamprin", "Schellenberg"),
            "Lithuania" => array("Vilnius", "Kaunas", "Klaipėda", "Šiauliai", "Panevėžys", "Alytus", "Marijampolė", "Mažeikiai", "Jonava", "Utena"),
            "Luxembourg" => array("Luxembourg City", "Esch-sur-Alzette", "Differdange", "Dudelange", "Ettelbruck", "Diekirch", "Wiltz", "Rumelange", "Grevenmacher", "Echternach"),
            "Malta" => array("Valletta", "Birkirkara", "Mosta", "Qormi", "Żabbar", "San Ġwann", "Zejtun", "Sliema", "Żebbuġ", "Rabat"),
            "Moldova" => array("Chișinău", "Tiraspol", "Bălți", "Bender", "Rîbnița", "Cahul", "Ungheni", "Soroca", "Orhei", "Dubăsari"),
            "Monaco" => array("Monaco-Ville", "Monte Carlo", "La Condamine", "Fontvieille", "Moneghetti", "Larvotto", "Saint Roman", "Jardin Exotique", "Les Révoires", "La Rousse"),
            "Montenegro" => array("Podgorica", "Nikšić", "Herceg Novi", "Pljevlja", "Budva", "Bar", "Bijelo Polje", "Cetinje", "Berane", "Ulcinj"),
            "Netherlands" => array("Amsterdam", "Rotterdam", "The Hague", "Utrecht", "Eindhoven", "Tilburg", "Groningen", "Almere Stad", "Breda", "Nijmegen"),
            "North Macedonia" => array("Skopje", "Bitola", "Kumanovo", "Prilep", "Tetovo", "Veles", "Ohrid", "Gostivar", "Štip", "Struga"),
            "Norway" => array("Oslo", "Bergen", "Stavanger", "Trondheim", "Drammen", "Fredrikstad", "Kristiansand", "Sandnes", "Tromsø", "Sarpsborg"),
            "Poland" => array("Warsaw", "Kraków", "Łódź", "Wrocław", "Poznań", "Gdańsk", "Szczecin", "Bydgoszcz", "Lublin", "Katowice"),
            "Portugal" => array("Lisbon", "Porto", "Vila Nova de Gaia", "Amadora", "Braga", "Setúbal", "Coimbra", "Queluz", "Funchal", "Cacém"),
            "Romania" => array("Bucharest", "Cluj-Napoca", "Timișoara", "Iași", "Constanța", "Craiova", "Brașov", "Galați", "Ploiești", "Oradea"),
            "Russia" => array("Moscow", "Saint Petersburg", "Novosibirsk", "Yekaterinburg", "Nizhny Novgorod", "Kazan", "Chelyabinsk", "Omsk", "Samara", "Rostov-on-Don"),
            "San Marino" => array("San Marino", "Serravalle", "Borgo Maggiore", "Domagnano", "Fiorentino", "Acquaviva", "Chiesanuova", "Montegiardino", "Faetano", "Pianello"),
            "Serbia" => array("Belgrade", "Novi Sad", "Niš", "Kragujevac", "Subotica", "Zrenjanin", "Pančevo", "Čačak", "Kraljevo", "Smederevo"),
            "Slovakia" => array("Bratislava", "Košice", "Prešov", "Žilina", "Nitra", "Banská Bystrica", "Trnava", "Martin", "Trenčín", "Poprad"),
            "Slovenia" => array("Ljubljana", "Maribor", "Celje", "Kranj", "Velenje", "Koper", "Novo Mesto", "Ptuj", "Trbovlje", "Kamnik"),
            "Spain" => array("Madrid", "Barcelona", "Valencia", "Seville", "Zaragoza", "Málaga", "Murcia", "Palma", "Las Palmas de Gran Canaria", "Bilbao"),
            "Sweden" => array("Stockholm", "Gothenburg", "Malmö", "Uppsala", "Västerås", "Örebro", "Linköping", "Helsingborg", "Jönköping", "Norrköping"),
            "Switzerland" => array("Zürich", "Geneva", "Basel", "Lausanne", "Bern", "Winterthur", "Lucerne", "St. Gallen", "Lugano", "Biel/Bienne"),
            "Turkey" => array("Istanbul", "Ankara", "Izmir", "Bursa", "Adana", "Gaziantep", "Konya", "Antalya", "Mersin", "Kayseri"),
            "Ukraine" => array("Kyiv", "Kharkiv", "Dnipro", "Odesa", "Donetsk", "Zaporizhzhia", "Lviv", "Kryvyi Rih", "Mykolaiv", "Mariupol"),
            "United Kingdom" => array("London", "Birmingham", "Manchester", "Glasgow", "Newcastle upon Tyne", "Sheffield", "Liverpool", "Leeds", "Bristol", "Edinburgh"),
            "Vatican City" => array("Vatican City"),
        );

        foreach ($european_countries as $country=>$cities){
            $this->command->getOutput()->write($country . " : ");
            foreach ($cities as $city){
                $this->command->getOutput()->write($city . " : ");
            }
        }


    }

}
