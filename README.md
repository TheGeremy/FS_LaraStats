<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Project

I try to build web based app called "FS Webstats". Goal here is to create web app to display various statistics or information from Farming Simulator 2019 gamesave.

- Overview dashboad (some key stats from gamesave)
- Farm statistics
- Financial 5 day overview
- Each animal husbandry information
- Varoius financial stats
- Information about farm property (vehicles, buildings, fuits, pallets and so on...)
- Information about fruit / product prices
- Information about missions
- Information about farm fields
- Forestry stats

Later (multiplayer version):

- Information about farms
- Information about players
- Back end management of players
- Back end management of farms
- Back end management of maps
- Back end management of fields
- Back end management of translations and labels

## Technical Requirements

- web server with PHP
- mariadb / mysql database
- savegame xml files accessible via FTP or network share

## Game XML Config Files Requirements

to be specified...

## Map MOD Config XML Files Requirements

For each map MOD you need to provide this files from map mod zip

- modDesc.xml
- farmlands.xml
- fillTypes.xml

If possible also translations files (cz/de/en)

- l10n_cz.xml
- l10n_de.xml
- l10n_en.xml

Files should be stored under /fs_config/config_map/[map config folder]/. For example you have map MOD >> FS19_SlovakVillage.zip >> map title is "Slovak Village" (from modDesc.xml) then path to map config folder is:

```/fs_config/config_map/map_slovak_village/```

farmlands.xml usual name "slovakVillage_farmlands.xml" needs to be changed to "farmlands.xml"

farmlands.xml usual structure:

```
<farmland id="1"  priceScale="0.75" npcName="NPC_SK_02" />	<!-- riverside parcel NW -->
<farmland id="2"  priceScale="1" npcName="NPC_SK_11" /> <!-- field 1 -->
<farmland id="3"  priceScale="1" npcName="NPC_SK_02" /> <!-- field 2 -->
<farmland id="4"  priceScale="1" npcName="NPC_SK_15" /> <!-- field 3 -->
<farmland id="5"  priceScale="1" npcName="NPC_SK_15" defaultFarmProperty="true" /> <!-- sheep pasture -->	
```

needs to be adjusted if you want to have more details to this:

```
<farmland id="1"  priceScale="0.75" npcName="NPC_SK_02" note="riverside parcel NW" />
<farmland id="2"  priceScale="1" npcName="NPC_SK_11" note="field 1" />
<farmland id="3"  priceScale="1" npcName="NPC_SK_02" note="field 2" />
<farmland id="4"  priceScale="1" npcName="NPC_SK_15" note="field 3" sizeHa="2.36"/>
<farmland id="5"  priceScale="1" npcName="NPC_SK_15" defaultFarmProperty="true" note="sheep pasture" />
<farmland id="6"  priceScale="0.5" npcName="NPC_SK_11" note="riverside parcel S (road bridge)" />	
```

You can manually change xml comment to note attribute, to be stored in database.
You can manually add attribute sizeHa (size of the field in Ha) to be loaded to database.
If those attributes are not provided, then the default value will be NULL.

## Other info

Project is under development. At the moment I just parse xml files and try to load them to database in nice structure.