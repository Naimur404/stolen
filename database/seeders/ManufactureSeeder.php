<?php

namespace Database\Seeders;

use App\Models\Manufacturer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ManufactureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $manufactures = [
            ["manufacturer_name" => "ACI HealthCare Limited"],
            ["manufacturer_name" => "Ad-din Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Adova Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Advanced Chemical Industries Limited"],
            ["manufacturer_name" => "Advent Pharma Ltd."],
            ["manufacturer_name" => "AFC Agro Biotech Ltd."],
            ["manufacturer_name" => "Al-Madina Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Albion Laboratories Ltd."],
            ["manufacturer_name" => "Alco Pharma Limited"],
            ["manufacturer_name" => "Alkad Laboratories"],
            ["manufacturer_name" => "Allied Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Ambee Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Amico Laboratories Ltd."],
            ["manufacturer_name" => "Amulet Pharmaceuticals Ltd."],
            ["manufacturer_name" => "APC Pharma Limited"],
            ["manufacturer_name" => "Apex Pharma Ltd."],
            ["manufacturer_name" => "Apollo Pharmaceutical Laboratories Ltd."],
            ["manufacturer_name" => "Arges Life Science Ltd."],
            ["manufacturer_name" => "Aristopharma Limited"],
            ["manufacturer_name" => "Aristopharma Limited, Gazipur"],
            ["manufacturer_name" => "Asiatic Laboratories Ltd."],
            ["manufacturer_name" => "Astra Biopharmaceuticals Ltd."],
            ["manufacturer_name" => "Avarox Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Aztec Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Bangladesh Antibiotic Industries Limited"],
            ["manufacturer_name" => "Beacon Cephalosporin Ltd."],
            ["manufacturer_name" => "Beacon Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Belsen Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Bengal Drugs & Chemical Works Pharm. Ltd."],
            ["manufacturer_name" => "Bengal Remedies Ltd."],
            ["manufacturer_name" => "Benham Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Beximco Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Beximco Pharmaceuticals Ltd., Kaliakoir"],
            ["manufacturer_name" => "Biogen Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Biopharma Ltd."],
            ["manufacturer_name" => "Bridge Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Bristol Pharma Ltd."],
            ["manufacturer_name" => "Centeon Pharma Limited"],
            ["manufacturer_name" => "Central Pharmaceutical Ltd."],
            ["manufacturer_name" => "Chemist Laboratories Ltd."],
            ["manufacturer_name" => "Cipla Ltd."],
            ["manufacturer_name" => "Concord Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Cosmic Pharma Ltd."],
            ["manufacturer_name" => "Cosmo Pharma Laboratories Ltd."],
            ["manufacturer_name" => "DBL Pharmaceuticals Limited"],
            ["manufacturer_name" => "Decent Pharma Laboratories Ltd."],
            ["manufacturer_name" => "Delta Pharma Limited"],
            ["manufacturer_name" => "Desh Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Doctor Tims Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Doctor's Chemicals Works Ltd."],
            ["manufacturer_name" => "Drug International Ltd."],
            ["manufacturer_name" => "Drug International Ltd. (Unit-3)"],
            ["manufacturer_name" => "Drug International Ltd. Unit-2"],
            ["manufacturer_name" => "EDCL (Bogra)"],
            ["manufacturer_name" => "EDCL (Dhaka)"],
            ["manufacturer_name" => "Edruc Ltd."],
            ["manufacturer_name" => "EMCS Pharma Limited"],
            ["manufacturer_name" => "Eon Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Eskayef Pharmaceuticals Ltd. Chandana, Gazipur"],
            ["manufacturer_name" => "Eskayef Pharmaceuticals Ltd. Mirpur."],
            ["manufacturer_name" => "Eskayef Pharmaceuticals Ltd., Narayanganj"],
            ["manufacturer_name" => "Eskayef Pharmaceuticals Ltd., Tongi,Gazipur"],
            ["manufacturer_name" => "Ethical Drug Ltd."],
            ["manufacturer_name" => "Euro Pharma Ltd."],
            ["manufacturer_name" => "Everest Pharmaceuticals Ltd."],
            ["manufacturer_name" => "FnF Pharmaceuticals Ltd."],
            ["manufacturer_name" => "G. A. Company Ltd."],
            ["manufacturer_name" => "General Pharmaceuticals Ltd, Unit-2"],
            ["manufacturer_name" => "General Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Gentry Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Genvio Pharma Ltd."],
            ["manufacturer_name" => "Get Well Limited"],
            ["manufacturer_name" => "Global Capsules Ltd."],
            ["manufacturer_name" => "Global Capsules Ltd., Gelatin Division"],
            ["manufacturer_name" => "Global Heavy Chemicals Ltd."],
            ["manufacturer_name" => "Globe Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Globex Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Gonoshasthaya Basic Chemical Ltd."],
            ["manufacturer_name" => "Gonoshasthaya Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Goodman Pharmaceuticals Ltd"],
            ["manufacturer_name" => "Greenland Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Guardian Healthcare Ltd."],
            ["manufacturer_name" => "Healthcare Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Hudson Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Ibn Sina Pharmaceutical Ind. Ltd."],
            ["manufacturer_name" => "Incepta Chemicals Ltd."],
            ["manufacturer_name" => "Incepta Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Incepta Pharmaceuticals Ltd. (Dhamrai Unit)"],
            ["manufacturer_name" => "Incepta Vaccine Limited"],
            ["manufacturer_name" => "Indo-Bangla Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Islam Oxygen (Pvt) Ltd."],
            ["manufacturer_name" => "Jayson Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Jenphar Bangladesh Ltd."],
            ["manufacturer_name" => "JMI Industrial Gas Ltd."],
            ["manufacturer_name" => "JMI Syringes & Medical Devices Ltd."],
            ["manufacturer_name" => "Kemiko Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Kumudini Pharma Ltd."],
            ["manufacturer_name" => "Labaid Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Leon Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Libra Infusion Limited"],
            ["manufacturer_name" => "Linde Bangladesh Limited"],
            ["manufacturer_name" => "Maks Drugs Ltd."],
            ["manufacturer_name" => "Marksman Pharmaceutical Ltd."],
            ["manufacturer_name" => "Medicon Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Medimet Pharmaceuticals Ltd."],
            ["manufacturer_name" => "MedRx Life Science Ltd."],
            ["manufacturer_name" => "Millat Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Modern Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Momotaz Pharmaceuticals Ltd."],
            ["manufacturer_name" => "MSF Pharmaceuticals Ltd.,"],
            ["manufacturer_name" => "MST Pharma and Healthcare Ltd."],
            ["manufacturer_name" => "Mundipharma (Bangladesh) Pvt. Ltd."],
            ["manufacturer_name" => "Mystic Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Naafco Pharma Ltd."],
            ["manufacturer_name" => "National Laboratories Ltd."],
            ["manufacturer_name" => "Navana Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Newtec Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Nip Chemicals And Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Nipa Pharmaceuticals Ltd."],
            ["manufacturer_name" => "NIPRO JMI Company Ltd."],
            ["manufacturer_name" => "NIPRO JMI Pharma Limited"],
            ["manufacturer_name" => "Novartis (Bangladesh) Ltd."],
            ["manufacturer_name" => "Novo Healthcare and Pharma Ltd."],
            ["manufacturer_name" => "Novo Healthcare and Pharma Ltd. (Unit-2)"],
            ["manufacturer_name" => "Novus Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Nuvista Pharma Ltd"],
            ["manufacturer_name" => "One Pharma Ltd."],
            ["manufacturer_name" => "Opso Saline Ltd."],
            ["manufacturer_name" => "Opsonin Bulk Drugs Ltd."],
            ["manufacturer_name" => "Opsonin Pharma Limited"],
            ["manufacturer_name" => "Organic Health Care Ltd."],
            ["manufacturer_name" => "Orion Infusion Ltd."],
            ["manufacturer_name" => "Orion Pharma Ltd."],
            ["manufacturer_name" => "Oyster Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Pacific Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Pharmacil Ltd."],
            ["manufacturer_name" => "Pharmasia Ltd."],
            ["manufacturer_name" => "Pharmatek Chemicals Ltd."],
            ["manufacturer_name" => "Pharmik Laboratories Ltd."],
            ["manufacturer_name" => "Popular Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Premier Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Prime Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Pristin Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Quality Pharmaceuticals (Pvt) Ltd."],
            ["manufacturer_name" => "Radiant Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Rahman Chemicals Ltd."],
            ["manufacturer_name" => "Rampart-Power Bangladesh Ltd."],
            ["manufacturer_name" => "Rangs Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Reckitt Benckiser Bangladesh Ltd."],
            ["manufacturer_name" => "Reman Drug Laboratories Ltd."],
            ["manufacturer_name" => "Renata Limited"],
            ["manufacturer_name" => "Renata Limited, Gazipur"],
            ["manufacturer_name" => "Rephco Pharmaceuticals Ltd."],
            ["manufacturer_name" => "RN Pharmaceuticals"],
            ["manufacturer_name" => "Royal Pharmaceuticals Ltd."],
            ["manufacturer_name" => "S. N. Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Sanofi Bangladesh Ltd."],
            ["manufacturer_name" => "Save Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Seba Laboratories Ltd."],
            ["manufacturer_name" => "Seema Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Sharif Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Shinil Pharma Limited"],
            ["manufacturer_name" => "Silco Pharmaceuticlas Ltd."],
            ["manufacturer_name" => "Silva Pharmaceuticals Ltd."],
            ["manufacturer_name" => "SMC Enterprise Limited"],
            ["manufacturer_name" => "Sodical Chemical Ltd."],
            ["manufacturer_name" => "Somatec Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Spectra Oxygen Limited"],
            ["manufacturer_name" => "Square Cephalosporins Ltd."],
            ["manufacturer_name" => "Square Formulations Ltd."],
            ["manufacturer_name" => "Square Pharmaceuticals Ltd. (Chemical Division)"],
            ["manufacturer_name" => "Square Pharmaceuticals Ltd. Gazipur"],
            ["manufacturer_name" => "Square Pharmaceuticals Ltd. Pabna"],
            ["manufacturer_name" => "Standard Laboratories Ltd."],
            ["manufacturer_name" => "Sun Pharmaceutical (Bangladesh) Ltd."],
            ["manufacturer_name" => "Sunman-Birdem Pharma Ltd."],
            ["manufacturer_name" => "Super Power Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Supreme Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Team Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Techno Drugs (Unit-3)"],
            ["manufacturer_name" => "Techno Drugs - Unit-2"],
            ["manufacturer_name" => "Techno Drugs Ltd."],
            ["manufacturer_name" => "The ACME Laboratories Ltd."],
            ["manufacturer_name" => "The White Horse Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Unimed Unihealth Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Union Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Unique Pharmaceutical Ltd."],
            ["manufacturer_name" => "United Chemicals & Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Veritas Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Virgo Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Vision Drugs Limited"],
            ["manufacturer_name" => "World Chemical Industry Ltd."],
            ["manufacturer_name" => "Zenith Pharmaceuticals Ltd."],
            ["manufacturer_name" => "Ziska Pharmaceuticals Ltd."],
        ];

        foreach($manufactures as $item){
            Manufacturer::firstOrCreate([
                'manufacturer_name' => $item['manufacturer_name'],
            ]);
        }

    }
}
