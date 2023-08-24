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
            $table->unsignedBigInteger('sort_id')->nullable();
        });

        DB::table('labs')->insert([
            ['panel_id' => '1', 'name' => 'WBC', 'label' => 'WBC', 'sort_id' => '1'],
            ['panel_id' => '1', 'name' => 'RBC', 'label' => 'RBC', 'sort_id' => '2'],
            ['panel_id' => '1', 'name' => 'HGB,Blood', 'label' => 'Hgb', 'sort_id' => '3'],
            ['panel_id' => '1', 'name' => 'HCT,Blood', 'label' => 'Hct', 'sort_id' => '4'],
            ['panel_id' => '1', 'name' => 'MCV', 'label' => 'MCV', 'sort_id' => '5'],
            ['panel_id' => '1', 'name' => 'MCH', 'label' => 'MCH', 'sort_id' => '6'],
            ['panel_id' => '1', 'name' => 'MCHC', 'label' => 'MCHC', 'sort_id' => '7'],
            ['panel_id' => '1', 'name' => 'PLT', 'label' => 'PLT', 'sort_id' => '8'],
            ['panel_id' => '1', 'name' => 'IPF', 'label' => 'IPF', 'sort_id' => '9'],
            ['panel_id' => '1', 'name' => 'RDW-CV', 'label' => 'RDW-CV', 'sort_id' => '9'],
            ['panel_id' => '1', 'name' => 'NRBC%', 'label' => 'NRBC%', 'sort_id' => '10'],
            ['panel_id' => '1', 'name' => 'NRBC#', 'label' => 'NRBC#', 'sort_id' => '11'],
            ['panel_id' => '1', 'name' => 'NEUTROPHILS %', 'label' => 'Neutrophils %', 'sort_id' => '12'],
            ['panel_id' => '1', 'name' => 'LYMPHOCYTES %', 'label' => 'Lymphocytes %', 'sort_id' => '13'],
            ['panel_id' => '1', 'name' => 'MONOCYTES %', 'label' => 'Monocytes %', 'sort_id' => '14'],
            ['panel_id' => '1', 'name' => 'EOSINOPHILS %', 'label' => 'Eosinophils %', 'sort_id' => '15'],
            ['panel_id' => '1', 'name' => 'BASOPHILS %', 'label' => 'Basophils %', 'sort_id' => '16'],
            ['panel_id' => '1', 'name' => 'IMMATURE GRANULOCYTE %', 'label' => 'Immature Granulocyte %', 'sort_id' => '17'],
            ['panel_id' => '1', 'name' => 'NEUTROPHILS #', 'label' => 'Neutrophils #', 'sort_id' => '18'],
            ['panel_id' => '1', 'name' => 'LYMPHOCYTES #', 'label' => 'Lymphocytes #', 'sort_id' => '19'],
            ['panel_id' => '1', 'name' => 'MONOCYTES #', 'label' => 'Eosinophils #', 'sort_id' => '20'],
            ['panel_id' => '1', 'name' => 'EOSINOPHILS #', 'label' => 'EOSINOPHILS #', 'sort_id' => '21'],
            ['panel_id' => '1', 'name' => 'BASOPHILS #', 'label' => 'Basophils #', 'sort_id' => '22'],
            ['panel_id' => '1', 'name' => 'IMMATURE GRANULOCYTE #', 'label' => 'Immature Granulocyte #', 'sort_id' => '23'],
            ['panel_id' => '1', 'name' => 'NEUTROPHILS %(M)', 'label' => 'Neutrophils %(M)', 'sort_id' => '24'],
            ['panel_id' => '1', 'name' => 'LYMPHOCYTES %(M)', 'label' => 'Lymphocytes %(M)', 'sort_id' => '25'],
            ['panel_id' => '1', 'name' => 'MONOCYTES %(M)', 'label' => 'Monocytes %(M)', 'sort_id' => '26'],
            ['panel_id' => '1', 'name' => 'EOSINOPHILS %(M)', 'label' => 'Eosinophils %(M)', 'sort_id' => '27'],
            ['panel_id' => '1', 'name' => 'BASOPHILS %(M)', 'label' => 'Basophils %(M)', 'sort_id' => '28'],
            ['panel_id' => '1', 'name' => 'MYELOCYTE %(M)', 'label' => 'Myelocyte %(M)', 'sort_id' => '29'],
            ['panel_id' => '1', 'name' => 'METAMYELOCYTE %(M)', 'label' => 'Metamyelocyte %(M)', 'sort_id' => '30'],
            ['panel_id' => '1', 'name' => 'NEUTROPHILS #(M)', 'label' => 'Neutrophils #(M)', 'sort_id' => '31'],
            ['panel_id' => '1', 'name' => 'LYMPHOCYTES #(M)', 'label' => 'Lymphocytes #(M)', 'sort_id' => '32'],
            ['panel_id' => '1', 'name' => 'MONOCYTES #(M)', 'label' => 'Monocytes #(M)', 'sort_id' => '33'],
            ['panel_id' => '1', 'name' => 'EOSINOPHILS #(M)', 'label' => 'Eosinophils #(M)', 'sort_id' => '34'],
            ['panel_id' => '1', 'name' => 'BASOPHILS #(M)', 'label' => 'Basophils #(M)', 'sort_id' => '35'],
            ['panel_id' => '1', 'name' => 'IMMATURE GRANULOCYTE #(M)', 'label' => 'Immature Granulocyte #(M)', 'sort_id' => '36'],
            ['panel_id' => '2', 'name' => 'RBC MORPHOLOGY', 'label' => 'RBC Morphology', 'sort_id' => '37'],
            ['panel_id' => '2', 'name' => 'PLT (ESTM)', 'label' => 'PLT (ESTM)', 'sort_id' => '38'],
            ['panel_id' => '2', 'name' => 'PLT MORPHOLOGY', 'label' => 'PLT Morphology', 'sort_id' => '39'],
            ['panel_id' => '2', 'name' => 'LARGE PLATELETS', 'label' => 'Large Platelets', 'sort_id' => '40'],
            ['panel_id' => '2', 'name' => 'POLYCHROMASIA', 'label' => 'Polychromasia', 'sort_id' => '41'],
            ['panel_id' => '2', 'name' => 'HYPOCHROMIA', 'label' => 'Hypochromia', 'sort_id' => '42'],
            ['panel_id' => '2', 'name' => 'ANISOCYTOSIS', 'label' => 'Anisocytosis', 'sort_id' => '43'],
            ['panel_id' => '2', 'name' => 'MICROCYTOSIS', 'label' => 'Microcytosis', 'sort_id' => '44'],
            ['panel_id' => '2', 'name' => 'MACROCYTOSIS', 'label' => 'Macrocytosis', 'sort_id' => '45'],
            ['panel_id' => '2', 'name' => 'POIKILOCYTOSIS', 'label' => 'Poikilocytosis', 'sort_id' => '46'],
            ['panel_id' => '2', 'name' => 'TARGET CELLS', 'label' => 'Target Cells', 'sort_id' => '47'],
            ['panel_id' => '2', 'name' => 'SCHISTOCYTES', 'label' => 'Schistocytes', 'sort_id' => '48'],
            ['panel_id' => '2', 'name' => 'SPHEROCYTES', 'label' => 'Spherocytes', 'sort_id' => '49'],
            ['panel_id' => '2', 'name' => 'OVALOCYTES', 'label' => 'Ovalocytes', 'sort_id' => '50'],
            ['panel_id' => '2', 'name' => 'STOMATOCYTE', 'label' => 'Stomatocyte', 'sort_id' => '51'],
            ['panel_id' => '2', 'name' => 'BURR CELLS', 'label' => 'Burr cells', 'sort_id' => '52'],
            ['panel_id' => '2', 'name' => 'ACANTHOCYTES', 'label' => 'Acanthocytes', 'sort_id' => '53'],
            ['panel_id' => '2', 'name' => 'TEARDROP CELLS', 'label' => 'Teardrop cells', 'sort_id' => '54'],
            ['panel_id' => '3', 'name' => 'GLUCOSE,Blood', 'label' => 'Glucose', 'sort_id' => '1'],
            ['panel_id' => '3', 'name' => 'GLUCOSE,POC', 'label' => 'Glucose, POC', 'sort_id' => '2'],
            ['panel_id' => '3', 'name' => 'SODIUM,Blood', 'label' => 'Na', 'sort_id' => '3'],
            ['panel_id' => '3', 'name' => 'POTASSIUM,Blood', 'label' => 'K', 'sort_id' => '4'],
            ['panel_id' => '3', 'name' => 'CHLORIDE,Blood', 'label' => 'Cl', 'sort_id' => '5'],
            ['panel_id' => '3', 'name' => 'CARBON DIOXIDE,Blood', 'label' => 'HCO3', 'sort_id' => '6'],
            ['panel_id' => '3', 'name' => 'UREA NITROGEN,Blood', 'label' => 'BUN', 'sort_id' => '7'],
            ['panel_id' => '3', 'name' => 'CREATININE,blood', 'label' => 'Cr', 'sort_id' => '8'],
            ['panel_id' => '3', 'name' => 'CALCIUM,Blood', 'label' => 'Calcium', 'sort_id' => '9'],
            ['panel_id' => '3', 'name' => 'ANION GAP,blood', 'label' => 'Anion Gap', 'sort_id' => '10'],
            ['panel_id' => '3', 'name' => 'EGFR,blood', 'label' => 'EGFR', 'sort_id' => '11'],
            ['panel_id' => '3', 'name' => 'EGFR CKD,blood', 'label' => 'EGFR', 'sort_id' => '12'],
            ['panel_id' => '4', 'name' => 'PROTEIN,TOTAL,Blood', 'label' => 'Protein', 'sort_id' => '66'],
            ['panel_id' => '4', 'name' => 'ALBUMIN ,Blood', 'label' => 'Albumin', 'sort_id' => '67'],
            ['panel_id' => '4', 'name' => 'BILIRUBIN,TOTAL,Blood', 'label' => 'T-Bili', 'sort_id' => '68'],
            ['panel_id' => '4', 'name' => 'BILIRUBIN,DIRECT,blood', 'label' => 'D-Bili', 'sort_id' => '69'],
            ['panel_id' => '4', 'name' => 'ALKP,Blood', 'label' => 'ALKP', 'sort_id' => '70'],
            ['panel_id' => '4', 'name' => 'ALT,Blood', 'label' => 'ALT', 'sort_id' => '71'],
            ['panel_id' => '4', 'name' => 'AST,Blood', 'label' => 'AST', 'sort_id' => '72'],
            ['panel_id' => '4', 'name' => 'MAGNESIUM,Blood', 'label' => 'Mg', 'sort_id' => '73'],
            ['panel_id' => '4', 'name' => 'PHOSPHORUS,Blood', 'label' => 'PO4', 'sort_id' => '74'],
            ['panel_id' => '5', 'name' => 'SODIUM,ISTAT', 'label' => 'Na', 'sort_id' => '75'],
            ['panel_id' => '5', 'name' => 'POTASSIUM,ISTAT', 'label' => 'K', 'sort_id' => '76'],
            ['panel_id' => '5', 'name' => 'PH @ 37C', 'label' => 'pH', 'sort_id' => '77'],
            ['panel_id' => '5', 'name' => 'PCO2 @ 37C', 'label' => 'PCO2', 'sort_id' => '78'],
            ['panel_id' => '5', 'name' => 'TCO2', 'label' => 'TCO2', 'sort_id' => '79'],
            ['panel_id' => '5', 'name' => 'PO2 @ 37C', 'label' => 'PO2', 'sort_id' => '80'],
            ['panel_id' => '5', 'name' => 'HCO3', 'label' => 'HCO3', 'sort_id' => '81'],
            ['panel_id' => '5', 'name' => 'BASE EXCESS (BE)', 'label' => 'Base Excess', 'sort_id' => '82'],
            ['panel_id' => '5', 'name' => 'O2 SAT%', 'label' => 'SpO2', 'sort_id' => '83'],
            ['panel_id' => '5', 'name' => 'FIO2', 'label' => 'FIO2', 'sort_id' => '84'],
            ['panel_id' => '6', 'name' => 'ANTI-Xa(UFH),BLOOD', 'label' => 'Anit-Xa (UFH)', 'sort_id' => '85'],
            ['panel_id' => '6', 'name' => 'PROTHROMBIN TIME,blood', 'label' => 'PT', 'sort_id' => '86'],
            ['panel_id' => '6', 'name' => 'INR,blood', 'label' => 'INR', 'sort_id' => '87'],
            ['panel_id' => '6', 'name' => 'APTT', 'label' => 'aPTT', 'sort_id' => '88'],
            ['panel_id' => '7', 'name' => 'BNP,BLOOD', 'label' => 'BNP', 'sort_id' => '89'],
            ['panel_id' => '7', 'name' => 'CK,Blood', 'label' => 'CK', 'sort_id' => '90'],
            ['panel_id' => '7', 'name' => 'CKMB,blood', 'label' => 'CKMB', 'sort_id' => '91'],
            ['panel_id' => '7', 'name' => 'CKMB INDEX,blood', 'label' => 'CKMB Index', 'sort_id' => '92'],
            ['panel_id' => '7', 'name' => 'MYOGLOBIN,Blood', 'label' => 'Myoglobin', 'sort_id' => '93'],
            ['panel_id' => '7', 'name' => 'TROPONIN-I,BLOOD', 'label' => 'Troponin-I', 'sort_id' => '94'],
            ['panel_id' => '8', 'name' => 'URINE GLUCOSE', 'label' => 'Urine Glucose', 'sort_id' => '95'],
            ['panel_id' => '8', 'name' => 'URINE PROTEIN', 'label' => 'Urine Protein', 'sort_id' => '96'],
            ['panel_id' => '8', 'name' => 'URINE BILIRUBIN', 'label' => 'Urine Bilirubin', 'sort_id' => '97'],
            ['panel_id' => '8', 'name' => 'URINE UROBILINOGEN', 'label' => 'Urine Urobilinogen', 'sort_id' => '98'],
            ['panel_id' => '8', 'name' => 'URINE PH', 'label' => 'Urine pH', 'sort_id' => '99'],
            ['panel_id' => '8', 'name' => 'URINE BLOOD', 'label' => 'Urine Blood', 'sort_id' => '100'],
            ['panel_id' => '8', 'name' => 'URINE KETONES', 'label' => 'Urine Ketones', 'sort_id' => '101'],
            ['panel_id' => '8', 'name' => 'URINE NITRITE', 'label' => 'Urine Nitrite', 'sort_id' => '102'],
            ['panel_id' => '8', 'name' => 'URINE LEUKOCYTE ESTERASE', 'label' => 'Urine Leukocyte Esterase', 'sort_id' => '103'],
            ['panel_id' => '8', 'name' => 'URINE CLARITY', 'label' => 'Urine Clarity', 'sort_id' => '104'],
            ['panel_id' => '8', 'name' => 'URINE SPECIFIC GRAVITY', 'label' => 'Urine Sp Gravity', 'sort_id' => '105'],
            ['panel_id' => '8', 'name' => 'URINE COLOR', 'label' => 'Urine Color', 'sort_id' => '106'],
            ['panel_id' => '8', 'name' => 'RBC/HPF', 'label' => 'RBC/Hpf', 'sort_id' => '107'],
            ['panel_id' => '8', 'name' => 'WBC/HPF', 'label' => 'WBC/Hpf', 'sort_id' => '108'],
            ['panel_id' => '8', 'name' => 'URINE BACTERIA', 'label' => 'Urine Bacteria', 'sort_id' => '109'],
            ['panel_id' => '8', 'name' => 'SQUAMOUS EPITHELIAL', 'label' => 'Squamous Epithelial', 'sort_id' => '110'],
            ['panel_id' => '8', 'name' => 'HYALINE CAST', 'label' => 'Hyaline Cast', 'sort_id' => '111'],
            ['panel_id' => '8', 'name' => 'CALCIUM OXALATE', 'label' => 'Calcium Oxalate', 'sort_id' => '112'],
            ['panel_id' => '9', 'name' => 'MRSA SURVL NARES DNA,E-SWAB', 'label' => 'MRSA Nares', 'sort_id' => '1'],
            ['panel_id' => '9', 'name' => 'MRSA SURVL NARES AGAR,E-SWAB', 'label' => 'MRSA Nares', 'sort_id' => '2'],
            ['panel_id' => '9', 'name' => 'C. DIFF TOX B GENE PCR,stool', 'label' => 'C. Diff Tox B PCR', 'sort_id' => '3'],
            ['panel_id' => '9', 'name' => 'VZ DNA', 'label' => 'VZV DNA PCR', 'sort_id' => '4'],
            ['panel_id' => '9', 'name' => 'HSV 1 DNA(QUAL)', 'label' => 'HSV 1 DNA', 'sort_id' => '5'],
            ['panel_id' => '9', 'name' => 'HSV 2 DNA(QUAL)', 'label' => 'HSV 2 DNA', 'sort_id' => '6'],
            ['panel_id' => '9', 'name' => 'HSV 1 DNA', 'label' => 'HSV 1 DNA', 'sort_id' => '7'],
            ['panel_id' => '9', 'name' => 'HSV 2 DNA', 'label' => 'HSV 2 DNA', 'sort_id' => '8'],
            ['panel_id' => '12', 'name' => 'ETHANOL,Urine', 'label' => 'EtOH', 'sort_id' => '115'],
            ['panel_id' => '12', 'name' => 'AMPHETAMINES SCREEN,urine', 'label' => 'Amphetamines', 'sort_id' => '116'],
            ['panel_id' => '12', 'name' => 'BARBITURATES SCREEN,urine', 'label' => 'Barbiturates', 'sort_id' => '117'],
            ['panel_id' => '12', 'name' => 'BENZODIAZEPINES SCREEN,urine', 'label' => 'Benzodiazepines', 'sort_id' => '118'],
            ['panel_id' => '12', 'name' => 'CANNABINOIDS SCREEN,urine', 'label' => 'Cannabinoids', 'sort_id' => '119'],
            ['panel_id' => '12', 'name' => 'COCAINE SCREEN,urine', 'label' => 'Cocaine', 'sort_id' => '120'],
            ['panel_id' => '12', 'name' => 'METHADONE SCREEN,urine', 'label' => 'Methadone', 'sort_id' => '121'],
            ['panel_id' => '12', 'name' => 'OPIATES SCREEN,urine', 'label' => 'Opiates', 'sort_id' => '122'],
            ['panel_id' => '12', 'name' => 'OXYCODONE SCREEN,urine', 'label' => 'Oxycodone', 'sort_id' => '123'],
            ['panel_id' => '12', 'name' => 'PHENCYCLIDINE SCREEN,urine', 'label' => 'Phencyclidine', 'sort_id' => '124'],
            ['panel_id' => '11', 'name' => 'CREATININE,Urine', 'label' => 'Creatinine, Urine', 'sort_id' => '1'],
            ['panel_id' => '11', 'name' => 'MICROALBUMIN,Urine', 'label' => 'MAU', 'sort_id' => '2'],
            ['panel_id' => '11', 'name' => 'MALB/CREAT RATIO,urine', 'label' => 'MAU/CR', 'sort_id' => '3'],
            ['panel_id' => '5', 'name' => 'TSH,BLOOD', 'label' => 'TSH', 'sort_id' => '1'],
            ['panel_id' => '5', 'name' => 'HEMOGLOBIN A1C,blood', 'label' => 'Hbg A1C', 'sort_id' => '2'],
            ['panel_id' => '5', 'name' => 'EAG', 'label' => 'Est Avg Glucose', 'sort_id' => '3'],
            ['panel_id' => '5', 'name' => 'AMMONIA,BLOOD', 'label' => 'Ammonia', 'sort_id' => '4'],
            ['panel_id' => '5', 'name' => 'LACTIC ACID,BLOOD', 'label' => 'Lactic Acid', 'sort_id' => '5'],
            ['panel_id' => '5', 'name' => 'LIPASE,BLOOD', 'label' => 'Lipase', 'sort_id' => '6'],
            ['panel_id' => '5', 'name' => 'ESR,BLOOD', 'label' => 'ESR', 'sort_id' => '7'],
            ['panel_id' => '5', 'name' => 'C-REACTIVE PROTEIN,BLOOD', 'label' => 'CRP', 'sort_id' => '8'],
            ['panel_id' => '5', 'name' => 'PROCALCITONIN,BLOOD', 'label' => 'Procalcitonin', 'sort_id' => '9'],
            ['panel_id' => '5', 'name' => 'VANCOMYCIN-TROUGH,BLOOD', 'label' => 'Vanco, Trough', 'sort_id' => '10'],
            ['panel_id' => '5', 'name' => 'VANCOMYCIN-RANDOM,BLOOD', 'label' => 'Vanco, Random', 'sort_id' => '11'],
            ['panel_id' => '5', 'name' => 'PROLACTIN,BLOOD', 'label' => 'Prolactin', 'sort_id' => '12'],
            ['panel_id' => '15', 'name' => 'FOLATE,Blood', 'label' => 'Folate', 'sort_id' => '136'],
            ['panel_id' => '15', 'name' => 'VITAMIN B12,Blood', 'label' => 'Vit B12', 'sort_id' => '137'],
            ['panel_id' => '15', 'name' => 'VITAMIN D 25-OH *TOTAL,Blood', 'label' => 'Vit D 25-OH', 'sort_id' => '138'],
            ['panel_id' => '9', 'name' => 'COVID-19 (BIOFIRE)', 'label' => 'COVID-19 PCR', 'sort_id' => '137'],
            ['panel_id' => '9', 'name' => 'COVID-19 (CEPHEID)', 'label' => 'COVID-19 PCR', 'sort_id' => '138'],
            ['panel_id' => '9', 'name' => 'COVID-19 ANTIGEN (BINAX)', 'label' => 'COVID-19 Antigen', 'sort_id' => '139'],
            ['panel_id' => '9', 'name' => 'COVID-19 PCR (FLUVID)', 'label' => 'COVID-19 PCR', 'sort_id' => '140'],
            ['panel_id' => '9', 'name' => 'FLU A PCR (FLUVID)', 'label' => 'FLU A PCR', 'sort_id' => '141'],
            ['panel_id' => '9', 'name' => 'FLU B PCR (FLUVID)', 'label' => 'FLU B PCR', 'sort_id' => '142'],
            ['panel_id' => '9', 'name' => 'RSV PCR (FLUVID)', 'label' => 'RSV PCR', 'sort_id' => '143'],
            ['panel_id' => '17', 'name' => 'CHOLESTEROL,Blood', 'label' => 'Total Cholesterol', 'sort_id' => '1'],
            ['panel_id' => '17', 'name' => 'TRIGLYCERIDE,Blood', 'label' => 'Triglyceride', 'sort_id' => '2'],
            ['panel_id' => '17', 'name' => 'HDL,blood', 'label' => 'HDL', 'sort_id' => '3'],
            ['panel_id' => '17', 'name' => 'LDL CALCULATION,blood', 'label' => 'LDL (calc)', 'sort_id' => '4'],
            ['panel_id' => '18', 'name' => 'OCCULT BLOOD RANDOM-GUAIAC', 'label' => 'Occult Blood', 'sort_id' => '1'],
        ]);

    }

    public function down()
    {
        Schema::dropIfExists('labs');
    }
};
