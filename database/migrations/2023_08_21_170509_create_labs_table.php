<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('labs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('panel_id')->constrained();
            $table->string('name');
            $table->string('label');
            $table->unsignedBigInteger('order_column')->nullable();
        });

        DB::table('labs')->insert([
            ['panel_id' => '1', 'name' => 'WBC', 'label' => 'WBC', 'order_column' => '1'],
            ['panel_id' => '1', 'name' => 'RBC', 'label' => 'RBC', 'order_column' => '2'],
            ['panel_id' => '1', 'name' => 'HGB,Blood', 'label' => 'Hgb', 'order_column' => '3'],
            ['panel_id' => '1', 'name' => 'HCT,Blood', 'label' => 'Hct', 'order_column' => '4'],
            ['panel_id' => '1', 'name' => 'MCV', 'label' => 'MCV', 'order_column' => '5'],
            ['panel_id' => '1', 'name' => 'MCH', 'label' => 'MCH', 'order_column' => '6'],
            ['panel_id' => '1', 'name' => 'MCHC', 'label' => 'MCHC', 'order_column' => '7'],
            ['panel_id' => '1', 'name' => 'PLT', 'label' => 'PLT', 'order_column' => '8'],
            ['panel_id' => '1', 'name' => 'IPF', 'label' => 'IPF', 'order_column' => '9'],
            ['panel_id' => '1', 'name' => 'RDW-CV', 'label' => 'RDW-CV', 'order_column' => '9'],
            ['panel_id' => '1', 'name' => 'NRBC%', 'label' => 'NRBC%', 'order_column' => '10'],
            ['panel_id' => '1', 'name' => 'NRBC#', 'label' => 'NRBC#', 'order_column' => '11'],
            ['panel_id' => '1', 'name' => 'NEUTROPHILS %', 'label' => 'Neutrophils %', 'order_column' => '12'],
            ['panel_id' => '1', 'name' => 'LYMPHOCYTES %', 'label' => 'Lymphocytes %', 'order_column' => '13'],
            ['panel_id' => '1', 'name' => 'MONOCYTES %', 'label' => 'Monocytes %', 'order_column' => '14'],
            ['panel_id' => '1', 'name' => 'EOSINOPHILS %', 'label' => 'Eosinophils %', 'order_column' => '15'],
            ['panel_id' => '1', 'name' => 'BASOPHILS %', 'label' => 'Basophils %', 'order_column' => '16'],
            [
                'panel_id' => '1', 'name' => 'IMMATURE GRANULOCYTE %', 'label' => 'Immature Granulocyte %',
                'order_column' => '17',
            ],
            ['panel_id' => '1', 'name' => 'NEUTROPHILS #', 'label' => 'Neutrophils #', 'order_column' => '18'],
            ['panel_id' => '1', 'name' => 'LYMPHOCYTES #', 'label' => 'Lymphocytes #', 'order_column' => '19'],
            ['panel_id' => '1', 'name' => 'MONOCYTES #', 'label' => 'Eosinophils #', 'order_column' => '20'],
            ['panel_id' => '1', 'name' => 'EOSINOPHILS #', 'label' => 'EOSINOPHILS #', 'order_column' => '21'],
            ['panel_id' => '1', 'name' => 'BASOPHILS #', 'label' => 'Basophils #', 'order_column' => '22'],
            [
                'panel_id' => '1', 'name' => 'IMMATURE GRANULOCYTE #', 'label' => 'Immature Granulocyte #',
                'order_column' => '23',
            ],
            ['panel_id' => '1', 'name' => 'NEUTROPHILS %(M)', 'label' => 'Neutrophils %(M)', 'order_column' => '24'],
            ['panel_id' => '1', 'name' => 'LYMPHOCYTES %(M)', 'label' => 'Lymphocytes %(M)', 'order_column' => '25'],
            ['panel_id' => '1', 'name' => 'MONOCYTES %(M)', 'label' => 'Monocytes %(M)', 'order_column' => '26'],
            ['panel_id' => '1', 'name' => 'EOSINOPHILS %(M)', 'label' => 'Eosinophils %(M)', 'order_column' => '27'],
            ['panel_id' => '1', 'name' => 'BASOPHILS %(M)', 'label' => 'Basophils %(M)', 'order_column' => '28'],
            ['panel_id' => '1', 'name' => 'MYELOCYTE %(M)', 'label' => 'Myelocyte %(M)', 'order_column' => '29'],
            [
                'panel_id' => '1', 'name' => 'METAMYELOCYTE %(M)', 'label' => 'Metamyelocyte %(M)',
                'order_column' => '30',
            ],
            ['panel_id' => '1', 'name' => 'NEUTROPHILS #(M)', 'label' => 'Neutrophils #(M)', 'order_column' => '31'],
            ['panel_id' => '1', 'name' => 'LYMPHOCYTES #(M)', 'label' => 'Lymphocytes #(M)', 'order_column' => '32'],
            ['panel_id' => '1', 'name' => 'MONOCYTES #(M)', 'label' => 'Monocytes #(M)', 'order_column' => '33'],
            ['panel_id' => '1', 'name' => 'EOSINOPHILS #(M)', 'label' => 'Eosinophils #(M)', 'order_column' => '34'],
            ['panel_id' => '1', 'name' => 'BASOPHILS #(M)', 'label' => 'Basophils #(M)', 'order_column' => '35'],
            [
                'panel_id' => '1', 'name' => 'IMMATURE GRANULOCYTE #(M)', 'label' => 'Immature Granulocyte #(M)',
                'order_column' => '36',
            ],
            ['panel_id' => '2', 'name' => 'RBC MORPHOLOGY', 'label' => 'RBC Morphology', 'order_column' => '37'],
            ['panel_id' => '2', 'name' => 'PLT (ESTM)', 'label' => 'PLT (ESTM)', 'order_column' => '38'],
            ['panel_id' => '2', 'name' => 'PLT MORPHOLOGY', 'label' => 'PLT Morphology', 'order_column' => '39'],
            ['panel_id' => '2', 'name' => 'LARGE PLATELETS', 'label' => 'Large Platelets', 'order_column' => '40'],
            ['panel_id' => '2', 'name' => 'POLYCHROMASIA', 'label' => 'Polychromasia', 'order_column' => '41'],
            ['panel_id' => '2', 'name' => 'HYPOCHROMIA', 'label' => 'Hypochromia', 'order_column' => '42'],
            ['panel_id' => '2', 'name' => 'ANISOCYTOSIS', 'label' => 'Anisocytosis', 'order_column' => '43'],
            ['panel_id' => '2', 'name' => 'MICROCYTOSIS', 'label' => 'Microcytosis', 'order_column' => '44'],
            ['panel_id' => '2', 'name' => 'MACROCYTOSIS', 'label' => 'Macrocytosis', 'order_column' => '45'],
            ['panel_id' => '2', 'name' => 'POIKILOCYTOSIS', 'label' => 'Poikilocytosis', 'order_column' => '46'],
            ['panel_id' => '2', 'name' => 'TARGET CELLS', 'label' => 'Target Cells', 'order_column' => '47'],
            ['panel_id' => '2', 'name' => 'SCHISTOCYTES', 'label' => 'Schistocytes', 'order_column' => '48'],
            ['panel_id' => '2', 'name' => 'SPHEROCYTES', 'label' => 'Spherocytes', 'order_column' => '49'],
            ['panel_id' => '2', 'name' => 'OVALOCYTES', 'label' => 'Ovalocytes', 'order_column' => '50'],
            ['panel_id' => '2', 'name' => 'STOMATOCYTE', 'label' => 'Stomatocyte', 'order_column' => '51'],
            ['panel_id' => '2', 'name' => 'BURR CELLS', 'label' => 'Burr cells', 'order_column' => '52'],
            ['panel_id' => '2', 'name' => 'ACANTHOCYTES', 'label' => 'Acanthocytes', 'order_column' => '53'],
            ['panel_id' => '2', 'name' => 'TEARDROP CELLS', 'label' => 'Teardrop cells', 'order_column' => '54'],
            ['panel_id' => '3', 'name' => 'GLUCOSE,Blood', 'label' => 'Glucose', 'order_column' => '1'],
            ['panel_id' => '3', 'name' => 'GLUCOSE,POC', 'label' => 'Glucose, POC', 'order_column' => '2'],
            ['panel_id' => '3', 'name' => 'SODIUM,Blood', 'label' => 'Na', 'order_column' => '3'],
            ['panel_id' => '3', 'name' => 'POTASSIUM,Blood', 'label' => 'K', 'order_column' => '4'],
            ['panel_id' => '3', 'name' => 'CHLORIDE,Blood', 'label' => 'Cl', 'order_column' => '5'],
            ['panel_id' => '3', 'name' => 'CARBON DIOXIDE,Blood', 'label' => 'HCO3', 'order_column' => '6'],
            ['panel_id' => '3', 'name' => 'UREA NITROGEN,Blood', 'label' => 'BUN', 'order_column' => '7'],
            ['panel_id' => '3', 'name' => 'CREATININE,blood', 'label' => 'Cr', 'order_column' => '8'],
            ['panel_id' => '3', 'name' => 'CALCIUM,Blood', 'label' => 'Calcium', 'order_column' => '9'],
            ['panel_id' => '3', 'name' => 'ANION GAP,blood', 'label' => 'Anion Gap', 'order_column' => '10'],
            ['panel_id' => '3', 'name' => 'EGFR,blood', 'label' => 'EGFR', 'order_column' => '11'],
            ['panel_id' => '3', 'name' => 'EGFR CKD,blood', 'label' => 'EGFR', 'order_column' => '12'],
            ['panel_id' => '4', 'name' => 'PROTEIN,TOTAL,Blood', 'label' => 'Protein', 'order_column' => '66'],
            ['panel_id' => '4', 'name' => 'ALBUMIN ,Blood', 'label' => 'Albumin', 'order_column' => '67'],
            ['panel_id' => '4', 'name' => 'BILIRUBIN,TOTAL,Blood', 'label' => 'T-Bili', 'order_column' => '68'],
            ['panel_id' => '4', 'name' => 'BILIRUBIN,DIRECT,blood', 'label' => 'D-Bili', 'order_column' => '69'],
            ['panel_id' => '4', 'name' => 'ALKP,Blood', 'label' => 'ALKP', 'order_column' => '70'],
            ['panel_id' => '4', 'name' => 'ALT,Blood', 'label' => 'ALT', 'order_column' => '71'],
            ['panel_id' => '4', 'name' => 'AST,Blood', 'label' => 'AST', 'order_column' => '72'],
            ['panel_id' => '4', 'name' => 'MAGNESIUM,Blood', 'label' => 'Mg', 'order_column' => '73'],
            ['panel_id' => '4', 'name' => 'PHOSPHORUS,Blood', 'label' => 'PO4', 'order_column' => '74'],
            ['panel_id' => '5', 'name' => 'SODIUM,ISTAT', 'label' => 'Na', 'order_column' => '75'],
            ['panel_id' => '5', 'name' => 'POTASSIUM,ISTAT', 'label' => 'K', 'order_column' => '76'],
            ['panel_id' => '5', 'name' => 'PH @ 37C', 'label' => 'pH', 'order_column' => '77'],
            ['panel_id' => '5', 'name' => 'PCO2 @ 37C', 'label' => 'PCO2', 'order_column' => '78'],
            ['panel_id' => '5', 'name' => 'TCO2', 'label' => 'TCO2', 'order_column' => '79'],
            ['panel_id' => '5', 'name' => 'PO2 @ 37C', 'label' => 'PO2', 'order_column' => '80'],
            ['panel_id' => '5', 'name' => 'HCO3', 'label' => 'HCO3', 'order_column' => '81'],
            ['panel_id' => '5', 'name' => 'BASE EXCESS (BE)', 'label' => 'Base Excess', 'order_column' => '82'],
            ['panel_id' => '5', 'name' => 'O2 SAT%', 'label' => 'SpO2', 'order_column' => '83'],
            ['panel_id' => '5', 'name' => 'FIO2', 'label' => 'FIO2', 'order_column' => '84'],
            ['panel_id' => '6', 'name' => 'ANTI-Xa(UFH),BLOOD', 'label' => 'Anit-Xa (UFH)', 'order_column' => '85'],
            ['panel_id' => '6', 'name' => 'PROTHROMBIN TIME,blood', 'label' => 'PT', 'order_column' => '86'],
            ['panel_id' => '6', 'name' => 'INR,blood', 'label' => 'INR', 'order_column' => '87'],
            ['panel_id' => '6', 'name' => 'APTT', 'label' => 'aPTT', 'order_column' => '88'],
            ['panel_id' => '7', 'name' => 'BNP,BLOOD', 'label' => 'BNP', 'order_column' => '89'],
            ['panel_id' => '7', 'name' => 'CK,Blood', 'label' => 'CK', 'order_column' => '90'],
            ['panel_id' => '7', 'name' => 'CKMB,blood', 'label' => 'CKMB', 'order_column' => '91'],
            ['panel_id' => '7', 'name' => 'CKMB INDEX,blood', 'label' => 'CKMB Index', 'order_column' => '92'],
            ['panel_id' => '7', 'name' => 'MYOGLOBIN,Blood', 'label' => 'Myoglobin', 'order_column' => '93'],
            ['panel_id' => '7', 'name' => 'TROPONIN-I,BLOOD', 'label' => 'Troponin-I', 'order_column' => '94'],
            ['panel_id' => '8', 'name' => 'URINE GLUCOSE', 'label' => 'Urine Glucose', 'order_column' => '95'],
            ['panel_id' => '8', 'name' => 'URINE PROTEIN', 'label' => 'Urine Protein', 'order_column' => '96'],
            ['panel_id' => '8', 'name' => 'URINE BILIRUBIN', 'label' => 'Urine Bilirubin', 'order_column' => '97'],
            [
                'panel_id' => '8', 'name' => 'URINE UROBILINOGEN', 'label' => 'Urine Urobilinogen',
                'order_column' => '98',
            ],
            ['panel_id' => '8', 'name' => 'URINE PH', 'label' => 'Urine pH', 'order_column' => '99'],
            ['panel_id' => '8', 'name' => 'URINE BLOOD', 'label' => 'Urine Blood', 'order_column' => '100'],
            ['panel_id' => '8', 'name' => 'URINE KETONES', 'label' => 'Urine Ketones', 'order_column' => '101'],
            ['panel_id' => '8', 'name' => 'URINE NITRITE', 'label' => 'Urine Nitrite', 'order_column' => '102'],
            [
                'panel_id' => '8', 'name' => 'URINE LEUKOCYTE ESTERASE', 'label' => 'Urine Leukocyte Esterase',
                'order_column' => '103',
            ],
            ['panel_id' => '8', 'name' => 'URINE CLARITY', 'label' => 'Urine Clarity', 'order_column' => '104'],
            [
                'panel_id' => '8', 'name' => 'URINE SPECIFIC GRAVITY', 'label' => 'Urine Sp Gravity',
                'order_column' => '105',
            ],
            ['panel_id' => '8', 'name' => 'URINE COLOR', 'label' => 'Urine Color', 'order_column' => '106'],
            ['panel_id' => '8', 'name' => 'RBC/HPF', 'label' => 'RBC/Hpf', 'order_column' => '107'],
            ['panel_id' => '8', 'name' => 'WBC/HPF', 'label' => 'WBC/Hpf', 'order_column' => '108'],
            ['panel_id' => '8', 'name' => 'URINE BACTERIA', 'label' => 'Urine Bacteria', 'order_column' => '109'],
            [
                'panel_id' => '8', 'name' => 'SQUAMOUS EPITHELIAL', 'label' => 'Squamous Epithelial',
                'order_column' => '110',
            ],
            ['panel_id' => '8', 'name' => 'HYALINE CAST', 'label' => 'Hyaline Cast', 'order_column' => '111'],
            ['panel_id' => '8', 'name' => 'CALCIUM OXALATE', 'label' => 'Calcium Oxalate', 'order_column' => '112'],
            [
                'panel_id' => '9', 'name' => 'MRSA SURVL NARES DNA,E-SWAB', 'label' => 'MRSA Nares',
                'order_column' => '1',
            ],
            [
                'panel_id' => '9', 'name' => 'MRSA SURVL NARES AGAR,E-SWAB', 'label' => 'MRSA Nares',
                'order_column' => '2',
            ],
            [
                'panel_id' => '9', 'name' => 'C. DIFF TOX B GENE PCR,stool', 'label' => 'C. Diff Tox B PCR',
                'order_column' => '3',
            ],
            ['panel_id' => '9', 'name' => 'VZ DNA', 'label' => 'VZV DNA PCR', 'order_column' => '4'],
            ['panel_id' => '9', 'name' => 'HSV 1 DNA(QUAL)', 'label' => 'HSV 1 DNA', 'order_column' => '5'],
            ['panel_id' => '9', 'name' => 'HSV 2 DNA(QUAL)', 'label' => 'HSV 2 DNA', 'order_column' => '6'],
            ['panel_id' => '9', 'name' => 'HSV 1 DNA', 'label' => 'HSV 1 DNA', 'order_column' => '7'],
            ['panel_id' => '9', 'name' => 'HSV 2 DNA', 'label' => 'HSV 2 DNA', 'order_column' => '8'],
            ['panel_id' => '12', 'name' => 'ETHANOL,Urine', 'label' => 'EtOH', 'order_column' => '115'],
            [
                'panel_id' => '12', 'name' => 'AMPHETAMINES SCREEN,urine', 'label' => 'Amphetamines',
                'order_column' => '116',
            ],
            [
                'panel_id' => '12', 'name' => 'BARBITURATES SCREEN,urine', 'label' => 'Barbiturates',
                'order_column' => '117',
            ],
            [
                'panel_id' => '12', 'name' => 'BENZODIAZEPINES SCREEN,urine', 'label' => 'Benzodiazepines',
                'order_column' => '118',
            ],
            [
                'panel_id' => '12', 'name' => 'CANNABINOIDS SCREEN,urine', 'label' => 'Cannabinoids',
                'order_column' => '119',
            ],
            ['panel_id' => '12', 'name' => 'COCAINE SCREEN,urine', 'label' => 'Cocaine', 'order_column' => '120'],
            ['panel_id' => '12', 'name' => 'METHADONE SCREEN,urine', 'label' => 'Methadone', 'order_column' => '121'],
            ['panel_id' => '12', 'name' => 'OPIATES SCREEN,urine', 'label' => 'Opiates', 'order_column' => '122'],
            ['panel_id' => '12', 'name' => 'OXYCODONE SCREEN,urine', 'label' => 'Oxycodone', 'order_column' => '123'],
            [
                'panel_id' => '12', 'name' => 'PHENCYCLIDINE SCREEN,urine', 'label' => 'Phencyclidine',
                'order_column' => '124',
            ],
            ['panel_id' => '11', 'name' => 'CREATININE,Urine', 'label' => 'Creatinine, Urine', 'order_column' => '1'],
            ['panel_id' => '11', 'name' => 'MICROALBUMIN,Urine', 'label' => 'MAU', 'order_column' => '2'],
            ['panel_id' => '11', 'name' => 'MALB/CREAT RATIO,urine', 'label' => 'MAU/CR', 'order_column' => '3'],
            ['panel_id' => '13', 'name' => 'TSH,BLOOD', 'label' => 'TSH', 'order_column' => '1'],
            ['panel_id' => '13', 'name' => 'HEMOGLOBIN A1C,blood', 'label' => 'Hbg A1C', 'order_column' => '2'],
            ['panel_id' => '13', 'name' => 'EAG', 'label' => 'Est Avg Glucose', 'order_column' => '3'],
            ['panel_id' => '13', 'name' => 'AMMONIA,BLOOD', 'label' => 'Ammonia', 'order_column' => '4'],
            ['panel_id' => '13', 'name' => 'LACTIC ACID,BLOOD', 'label' => 'Lactic Acid', 'order_column' => '13'],
            ['panel_id' => '13', 'name' => 'LIPASE,BLOOD', 'label' => 'Lipase', 'order_column' => '6'],
            ['panel_id' => '13', 'name' => 'ESR,BLOOD', 'label' => 'ESR', 'order_column' => '7'],
            ['panel_id' => '13', 'name' => 'C-REACTIVE PROTEIN,BLOOD', 'label' => 'CRP', 'order_column' => '8'],
            ['panel_id' => '13', 'name' => 'PROCALCITONIN,BLOOD', 'label' => 'Procalcitonin', 'order_column' => '9'],
            [
                'panel_id' => '13', 'name' => 'VANCOMYCIN-TROUGH,BLOOD', 'label' => 'Vanco, Trough',
                'order_column' => '10',
            ],
            [
                'panel_id' => '13', 'name' => 'VANCOMYCIN-RANDOM,BLOOD', 'label' => 'Vanco, Random',
                'order_column' => '11',
            ],
            ['panel_id' => '13', 'name' => 'PROLACTIN,BLOOD', 'label' => 'Prolactin', 'order_column' => '12'],
            ['panel_id' => '15', 'name' => 'FOLATE,Blood', 'label' => 'Folate', 'order_column' => '136'],
            ['panel_id' => '15', 'name' => 'VITAMIN B12,Blood', 'label' => 'Vit B12', 'order_column' => '137'],
            [
                'panel_id' => '15', 'name' => 'VITAMIN D 25-OH *TOTAL,Blood', 'label' => 'Vit D 25-OH',
                'order_column' => '138',
            ],
            ['panel_id' => '9', 'name' => 'COVID-19 (BIOFIRE)', 'label' => 'COVID-19 PCR', 'order_column' => '137'],
            ['panel_id' => '9', 'name' => 'COVID-19 (CEPHEID)', 'label' => 'COVID-19 PCR', 'order_column' => '138'],
            [
                'panel_id' => '9', 'name' => 'COVID-19 ANTIGEN (BINAX)', 'label' => 'COVID-19 Antigen',
                'order_column' => '139',
            ],
            ['panel_id' => '9', 'name' => 'COVID-19 PCR (FLUVID)', 'label' => 'COVID-19 PCR', 'order_column' => '140'],
            ['panel_id' => '9', 'name' => 'FLU A PCR (FLUVID)', 'label' => 'FLU A PCR', 'order_column' => '141'],
            ['panel_id' => '9', 'name' => 'FLU B PCR (FLUVID)', 'label' => 'FLU B PCR', 'order_column' => '142'],
            ['panel_id' => '9', 'name' => 'RSV PCR (FLUVID)', 'label' => 'RSV PCR', 'order_column' => '143'],
            ['panel_id' => '17', 'name' => 'CHOLESTEROL,Blood', 'label' => 'Total Cholesterol', 'order_column' => '1'],
            ['panel_id' => '17', 'name' => 'TRIGLYCERIDE,Blood', 'label' => 'Triglyceride', 'order_column' => '2'],
            ['panel_id' => '17', 'name' => 'HDL,blood', 'label' => 'HDL', 'order_column' => '3'],
            ['panel_id' => '17', 'name' => 'LDL CALCULATION,blood', 'label' => 'LDL (calc)', 'order_column' => '4'],
            [
                'panel_id' => '18', 'name' => 'OCCULT BLOOD RANDOM-GUAIAC', 'label' => 'Occult Blood',
                'order_column' => '1',
            ],
        ]);

    }

    public function down()
    {
        Schema::dropIfExists('labs');
    }
};
