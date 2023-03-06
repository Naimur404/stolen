<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MedicineCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = array(
            'Tablet',
            'Capsule',
            'Dispersible Tablet',
            'Powder For Suspension',
            'Syrup',
            'Suspension',
            'Paediatric Drops',
            'Lotion',
            'Ointment',
            'Oral Saline',
            'Cream',
            'Gel',
            'Suppository',
            'Solution',
            'Bolus',
            'Powder',
            'Water Soluble Powder',
            'Oral Solution',
            'Oral Powder',
            'Eye and Ear Drops',
            'IM Injection',
            'IV Injection',
            'Injection',
            'IV/IM Injection',
            'Eye, Ear & Nasal Drops',
            'Vaginal Tablet',
            'Eye Drops',
            'Hand Rub',
            'Oral Gel',
            'Gargle & Mouth Wash',
            'Xr Tablet',
            'Sr Tablet',
            'Raw Materials',
            'Pellets',
            'Eye Ointment',
            'Mouth Wash',
            'Sr Capsule',
            'Aerosol Inhalation',
            'Inhaler',
            'Inhalation Solution',
            'IV Infusion',
            'Ors Tablet',
            'Orodispersible Tablet',
            'Nebuliser Solution',
            'Oral Paste',
            'Nasal Spray',
            'Spray',
            'Dr Tablet',
            'Cozycap',
            'Inhalation Aerosol',
            'Inhalation Capsule',
            'Nasal Drops',
            'Chewable Tablet',
            'Topical Solution',
            'Cr Tablet',
            'Er Tablet',
            'Scalp Lotion',
            'Oral Drops',
            'Granules For Suspension',
            'Oral Suspension',
            'Mups Tablet',
            'Liquid',
            'Emulsion',
            'Oral Liquid',
            'Pellets For Suspension',
            'Sachet',
            'Elixir',
            'Linctus',
            'Sached Powder',
            'Ear Drop',
            'Eye & Nasal Drops',
            'Shampoo',
            'Ophthalmic Emulsion',
            'Eye Gel',
            'Solution For Injection',
            'Nebuliser Suspension',
            'Solution For Infusion',
            'Soft Gelatin Capsule',
            'Odt Tablet',
            'Irrigation Solution',
            'Rectal Ointment',
            'Resperitory Solution',
            'Vaginal Cream',
            'Respirator Suspension',
            'Oral Soluble Film',
            'Emulgel',
            'Mouth Dissolving Tablet',
            'Oral Emulsion',
            'Rapid Tablet',
            'Effervescent Tablet',
            'Dry Powder Inhaler',
            'Powder for Pedriatric Drop',
            'Effervescent Granules',
            'Mouth Wash Antiseptic',
            'Syringe',
            'Dialysis Solution',
            'Per Rectal',
            'Vaginal Gel',
            'Pr Tablet',
            'Dr Granules For Suspension',
            'Er Capsule',
            'Extended Release Capsule',
            'Vaccine',
            'Gas',
            'Tincture',
            'Scrub',
            'Blood bag',
            'Pvc Bag',
            'Powder for Solution',
            'Pour On (Solution)',
            'Ear Spray',
            'Blood Tubing Set',
            'Needle for Syringe',
            'Butterfly',
            'Powder For Oral Solution',
            'Oral Granules',
            'Eye Cleanser Solution',
            'Eye and Ear Ointment',
            'Delayed Release Capsule',
            'Water For Injection',
            'Vaginal Pessary',
            'Metered Dose Inhaler',
            'Gum',
            'Oral Dental Gel',
            'Topical Suspension',
            'Cr Capsule',
            'Md Tablet',
            'Inhalation Liquid',
            'Viscoelastic Solution',
            'Drops',
            'Vaginal Suppository',
            'Scalp Ointment',
            'Sprinkle Capsule',
            'M R Capsule',
            'M R Tablet',
            'Repacking',
        );

        foreach ($categories as $category){
            Category::firstOrCreate([
                'category_name' => $category,
            ]);
        }
    }
}
