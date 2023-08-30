const testData =
    'Printed at:\n' +
    'FRESNO VA MEDICAL CENTER [CLIA# 05D0988207]\n' +
    '2615 EAST CLINTON AVE FRESNO, CA 93703-2223\n' +
    'As of: Aug 16, 2023@08:53\n' +
    '\n' +
    '\n' +
    '\n' +
    'Reporting Lab: FRESNO VA MEDICAL CENTER [CLIA# 05D0988207]\n' +
    '               2615 EAST CLINTON AVE FRESNO, CA 93703-2223\n' +
    '\n' +
    'Report Released Date/Time: Aug 16, 2023@06:32\n' +
    'Provider: JAIN,NAMAN\n' +
    '  Specimen: PLASMA.           CH 0816 30\n' +
    '    Specimen Collection Date: Aug 16, 2023\n' +
    '      Test name                Result    units      Ref.   range   Site Code\n' +
    'GLUCOSE,Blood                   104     mg/dL      70 - 105         [570]\n' +
    'SODIUM,Blood                    140     mmol/L     136 - 145        [570]\n' +
    'POTASSIUM,Blood                 4.0     mmol/L     3.5 - 5.1        [570]\n' +
    'CHLORIDE,Blood                  100 L   mmol/L     101 - 111        [570]\n' +
    'CARBON DIOXIDE,Blood             31     mmol/L     24 - 32          [570]\n' +
    'UREA NITROGEN,Blood             190 H*  mg/dL      7 - 24           [570]\n' +
    'CREATININE,blood                1.0     mg/dL      0.6 - 1.3        [570]\n' +
    'CALCIUM,Blood                   9.6     mg/dL      8.4 - 10.2       [570]\n' +
    'ANION GAP,blood                  13     mmol/L     9 - 17           [570]\n' +
    'EGFR CKD,blood                   82                Ref: >=60        [570]\n' +
    '      Eval: Estimated Glomerular Filtration Rate (eGFR) calculated using the\n' +
    '      Eval: 2021 Chronic Kidney Disease-Epidemiology (CKD-EPI) Collaboration\n' +
    '      Eval: creatinine equation; units of measure are mL/min/1.73 m2.\n' +
    '      Eval:\n' +
    '      Eval: Results are only valid for adults (=18 years) whose serum\n' +
    '      Eval: creatinine is in a steady state.  eGFR calculations are not valid\n' +
    '      Eval: for patients with acute kidney injury and for patients on\n' +
    '      Eval: dialysis.  Creatinine-based estimates of kidney function may also\n' +
    '      Eval: be inaccurate in patients with reduced creatinine generation\n' +
    '      Eval: due to decreased muscle mass (e.g., malnutrition, severe\n' +
    '      Eval: hypoalbuminemia, sarcopenia, chronic neuromuscular disease,\n' +
    '      Eval: amputations, severe heart failure or liver disease) and in\n' +
    '      Eval: patients with increased creatinine generation due to increased\n' +
    '      Eval: muscle mass (e.g., muscle builders, anabolic steroids) or\n' +
    '      Eval: increased dietary intake.\n' +
    '      Eval:\n' +
    '      Eval: As drug clearance is proportional to total GFR and not GFR indexed\n' +
    '      Eval: to body surface area (BSA), in individuals with a BSA substantially\n' +
    '      Eval: different than 1.73 m2, drug dosing should be based the reported\n' +
    '      Eval: eGFR value de-indexed from BSA by multiplying by the individual\'s\n' +
    '      Eval: BSA and dividing by 1.73.\n' +
    '      Eval:\n' +
    '      Eval: CKD is diagnosed based on abnormalities of kidney structure or\n' +
    '      Eval: function, present for >3 months, with implications for health and\n' +
    '      Eval: disease. CKD is classified and staged based on cause, eGFR and\n' +
    '      Eval: albuminuria (quantified as urine albumin to creatinine ratio). An\n' +
    '      Eval: eGFR >60 mL/min/1.73 m2 in the absence of increased urine albumin\n' +
    '      Eval: excretion or structural abnormalities does not represent CKD.\n' +
    '      Eval:\n' +
    '      Eval: eGFR(mL/min/1.73 m2)     CKD STAGE     INTERPRETATION\n' +
    '      Eval: -------------------------------------------------------------------\n' +
    '      Eval: >=90                     G1            Normal\n' +
    '      Eval: 60-89                    G2            Mild decrease\n' +
    '      Eval: 45-59                    G3A           Mild to moderate decrease\n' +
    '      Eval: 30-44                    G3B           Moderate to severe decrease\n' +
    '      Eval: 15-29                    G4            Severe decrease\n' +
    '      Eval: <15                      G5            Kidney failure\n' +
    'PROTEIN,TOTAL,Blood             8.2     g/dL       6.7 - 8.6        [570]\n' +
    'ALBUMIN ,Blood                  3.8     g/dL       3.6 - 4.8        [570]\n' +
    'BILIRUBIN,TOTAL,Blood           1.0     mg/dL      0.2 - 1.0        [570]\n' +
    'BILIRUBIN,DIRECT,blood          0.3 H   mg/dL      Ref: <=0.2       [570]\n' +
    'ALKP,Blood                      193 H   IU/L       36 - 109         [570]\n' +
    'ALT,Blood                       110 H   IU/L       10 - 40          [570]\n' +
    'AST,Blood                        83 H   IU/L       10 - 42          [570]\n' +
    'MAGNESIUM,Blood                 2.2     mg/dL      1.8 - 2.5        [570]\n' +
    'PHOSPHORUS,Blood                5.5 H   mg/dL      2.2 - 4.2        [570]\n' +
    '===============================================================================\n' +
    '\n' +
    '\n' +
    '\n' +
    'Reporting Lab: FRESNO VA MEDICAL CENTER [CLIA# 05D0988207]\n' +
    '               2615 EAST CLINTON AVE FRESNO, CA 93703-2223\n' +
    '\n' +
    'Report Released Date/Time: Aug 16, 2023@05:51\n' +
    'Provider: JAIN,NAMAN\n' +
    '  Specimen: BLOOD.            HE 0816 18\n' +
    '    Specimen Collection Date: Aug 16, 2023\n' +
    '      Test name                Result    units      Ref.   range   Site Code\n' +
    'WBC                            10.8     10*3/uL    4.0 - 11.0       [570]\n' +
    'RBC                             5.0     10*6/uL    4.7 - 6.1        [570]\n' +
    'HGB,Blood                      14.1     g/dL       14.0 - 17.0      [570]\n' +
    'HCT,Blood                      45.1     %          42.0 - 52.0      [570]\n' +
    'MCV                              91     fL         80 - 94          [570]\n' +
    'MCH                              28     pg         27 - 31          [570]\n' +
    'MCHC                             31 L   g/dL       32 - 36          [570]\n' +
    'RDW-CV                         15.4     %          11.5 - 20.0      [570]\n' +
    'PLT                             263     K/cmm      150 - 400        [570]\n' +
    'NRBC%                           0.0     %          0.0 - 0.2        [570]\n' +
    'NRBC#                          0.00     10*3/uL    0.00 - 0.01      [570]\n' +
    'NEUTROPHILS %                  66.2 H   %          36.0 - 66.0      [570]\n' +
    'LYMPHOCYTES %                  21.1 L   %          24.0 - 44.0      [570]\n' +
    'MONOCYTES %                     7.7 H   %          0.0 - 6.0        [570]\n' +
    'EOSINOPHILS %                   3.8 H   %          0.0 - 3.0        [570]\n' +
    'BASOPHILS %                     0.5     %          0.0 - 1.0        [570]\n' +
    'IMMATURE GRANULOCYTE %          0.7     %          0.0 - 0.9        [570]\n' +
    'NEUTROPHILS #                  7.17     10*3/uL    1.44 - 7.26      [570]\n' +
    'LYMPHOCYTES #                  2.28     10*3/uL    0.96 - 4.84      [570]\n' +
    'MONOCYTES #                    0.83 H   10*3/uL    0.00 - 0.66      [570]\n' +
    'EOSINOPHILS #                  0.41 H   10*3/uL    0.00 - 0.33      [570]\n' +
    'BASOPHILS #                    0.05     10*3/uL    0.00 - 0.11      [570]\n' +
    'IMMATURE GRANULOCYTE #         0.08     10*3/uL    0.00 - 0.10      [570]\n' +
    'Comment: Automated count - smear not reviewed.\n' +
    '===============================================================================\n' +
    '\n' +
    '\n' +
    '\n' +
    'Reporting Lab: FRESNO VA MEDICAL CENTER [CLIA# 05D0988207]\n' +
    '               2615 EAST CLINTON AVE FRESNO, CA 93703-2223\n' +
    '\n' +
    'Report Released Date/Time: Aug 04, 2023@04:14\n' +
    'Provider: FIRDAUS,MUHAMMAD\n' +
    '  Specimen: BLOOD.            HE 0804 4\n' +
    '    Specimen Collection Date: Aug 04, 2023@03:40\n' +
    '      Test name                Result    units      Ref.   range   Site Code\n' +
    'WBC                            18.3 H   10*3/uL    4.0 - 11.0       [570]\n' +
    'RBC                             2.9 L   10*6/uL    4.7 - 6.1        [570]\n' +
    'HGB,Blood                       9.9 L   g/dL       14.0 - 17.0      [570]\n' +
    'HCT,Blood                      30.8 L   %          42.0 - 52.0      [570]\n' +
    'MCV                             107 H   fL         80 - 94          [570]\n' +
    'MCH                              34 H   pg         27 - 31          [570]\n' +
    'MCHC                             32     g/dL       32 - 36          [570]\n' +
    'RDW-CV                         16.3     %          11.5 - 20.0      [570]\n' +
    'PLT                             206     K/cmm      150 - 400        [570]\n' +
    'NEUTROPHILS %(M)               96.5 H   %          36.0 - 66.0      [570]\n' +
    'LYMPHOCYTES %(M)                0.9 L   %          24.0 - 44.0      [570]\n' +
    'MONOCYTES %(M)                  1.7     %          0.0 - 6.0        [570]\n' +
    'EOSINOPHILS %(M)                0.0     %          0.0 - 3.0        [570]\n' +
    'BASOPHILS %(M)                  0.0     %          0.0 - 1.0        [570]\n' +
    'MYELOCYTE %(M)                  0.9 H   %          Ref: <=0.0       [570]\n' +
    'NEUTROPHILS #(M)              17.66 H   10*3/uL    1.44 - 7.26      [570]\n' +
    'LYMPHOCYTES #(M)               0.16 L   10*3/uL    0.96 - 4.84      [570]\n' +
    'MONOCYTES #(M)                 0.31     10*3/uL    0.00 - 0.66      [570]\n' +
    'EOSINOPHILS #(M)               0.00     10*3/uL    0.00 - 0.33      [570]\n' +
    'BASOPHILS #(M)                 0.00     10*3/uL    0.00 - 0.11      [570]\n' +
    'IMMATURE GRANULOCYTE #(M)      0.16 H   10*3/uL    0.00 - 0.10      [570]\n' +
    'RBC MORPHOLOGY             Abnormal                Ref: Normal      [570]\n' +
    'PLT (ESTM)                 Adequate                Ref: Adequate    [570]\n' +
    'PLT MORPHOLOGY               Normal                Ref: Normal      [570]\n' +
    'ANISOCYTOSIS                     1+ H              Ref: None        [570]\n' +
    'MACROCYTOSIS                     1+ H              Ref: None        [570]\n' +
    'POIKILOCYTOSIS                   1+ H              Ref: None        [570]\n' +
    'SCHISTOCYTES                     1+ H              Ref: None        [570]\n' +
    'BURR CELLS                       1+ H              Ref: None        [570]\n' +
    'Comment: Computer Assisted Manual differential count performed by Medical\n' +
    '        Technologist\n' +
    '        NEUT# includes all mature neutrophils & bands counted in the\n' +
    '        differential.\n' +
    '        LYMPH# includes all mature lymphocytes counted in the differential.\n' +
    '        IG# includes all Immature Granulocytes counted in the differential\n' +
    '===============================================================================\n' +
    '\n' +
    '\n' +
    '                            ---- MICROBIOLOGY ----\n' +
    'Printed at:\n' +
    'FRESNO VA MEDICAL CENTER [CLIA# 05D0988207]\n' +
    '2615 EAST CLINTON AVE FRESNO, CA 93703-2223\n' +
    'As of: Aug 15, 2023@09:20\n' +
    '\n' +
    '\n' +
    '\n' +
    'Reporting Lab: FRESNO VA MEDICAL CENTER [CLIA# 05D0988207]\n' +
    '               2615 EAST CLINTON AVE FRESNO, CA 93703-2223\n' +
    '\n' +
    'Accession [UID]: MYCO 23 248 [4423000248]   Received: Aug 05, 2023@17:17\n' +
    'Collection sample: BRONCHIAL LAVAGE    Collection date: Aug 05, 2023 16:20\n' +
    'Provider: ASLAM,WAQAS\n' +
    'Comment on specimen:\n' +
    '*FUNGAL CULTURE,BRONCHIAL Merged: Aug 05, 2023@17:46 by 423592\n' +
    '\n' +
    '\n' +
    ' Test(s) ordered: FUNGAL CULTURE,BRONCHIAL...... completed: Aug 05, 2023 17:46\n' +
    '\n' +
    '===============================================================================\n' +
    '\n' +
    '\n' +
    '\n' +
    'Reporting Lab: FRESNO VA MEDICAL CENTER [CLIA# 05D0988207]\n' +
    '               2615 EAST CLINTON AVE FRESNO, CA 93703-2223\n' +
    '\n' +
    'Report Released Date/Time: Aug 01, 2023@23:29\n' +
    'Provider: NUDANU,JASMINE B\n' +
    '  Specimen: NASOPHARYNX.      PA 23 228\n' +
    '    Specimen Collection Date: Jul 31, 2023@08:30\n' +
    '      Test name                Result    units      Ref.   range   Site Code\n' +
    'COVID-19 (BIOFIRE)         Not detected\n' +
    '                                                   Ref: not detected\n' +
    '                                                                    [640]\n' +
    'INF A: H1                  Not detected\n' +
    '                                                   Ref: not detected\n' +
    '                                                                    [640]\n' +
    'B. PARAPERTUSSIS           Not detected\n' +
    '                                                   Ref: not detected\n' +
    '                                                                    [640]\n' +
    'ADENOVIRUS                 Not detected\n' +
    '                                                   Ref: not detected\n' +
    '                                                                    [640]\n' +
    'CORONAVIRUS 229E           Not detected\n' +
    '                                                   Ref: not detected\n' +
    '                                                                    [640]\n' +
    'CORONAVIRUS HKU1           Not detected\n' +
    '                                                   Ref: not detected\n' +
    '                                                                    [640]\n' +
    'CORONAVIRUS NL63           Not detected\n' +
    '                                                   Ref: not detected\n' +
    '                                                                    [640]\n' +
    'CORONAVIRUS OC43           Not detected\n' +
    '                                                   Ref: not detected\n' +
    '                                                                    [640]\n' +
    'INFLUENZA A                Not detected\n' +
    '                                                   Ref: not detected\n' +
    '                                                                    [640]\n' +
    'INF A: H3                  Not detected\n' +
    '                                                   Ref: not detected\n' +
    '                                                                    [640]\n' +
    'INF A: H1-2009             Not detected\n' +
    '                                                   Ref: not detected\n' +
    '                                                                    [640]\n' +
    'INFLUENZA B                Not detected\n' +
    '                                                   Ref: not detected\n' +
    '                                                                    [640]\n' +
    'HUMAN METAPNEUMOVIRUS      Not detected\n' +
    '                                                   Ref: not detected\n' +
    '                                                                    [640]\n' +
    'HUMAN RHINOVIRUS-ENTEROVIRUSNot detected\n' +
    '                                                   Ref: not detected\n' +
    '                                                                    [640]\n' +
    'HUMAN PARAINFLU VIRUS 1    Not detected\n' +
    '                                                   Ref: not detected\n' +
    '                                                                    [640]\n' +
    'HUMAN PARAINFLU VIRUS 2    Not detected\n' +
    '                                                   Ref: not detected\n' +
    '                                                                    [640]\n' +
    'HUMAN PARAINFLU VIRUS 3    Not detected\n' +
    '                                                   Ref: not detected\n' +
    '                                                                    [640]\n' +
    'HUMAN PARAINFLU VIRUS 4    Not detected\n' +
    '                                                   Ref: not detected\n' +
    '                                                                    [640]\n' +
    'RESPIRATORY SYNCYTIAL VIRUSNot detected\n' +
    '                                                   Ref: not detected\n' +
    '                                                                    [640]\n' +
    'BORDETELLA PERTUSSIS       Not detected\n' +
    '                                                   Ref: not detected\n' +
    '                                                                    [640]\n' +
    'CHLAMYDOPHILA PNEUMONIAE   Not detected\n' +
    '                                                   Ref: not detected\n' +
    '                                                                    [640]\n' +
    'MYCOPLASMA PNEUMONIAE      Not detected\n' +
    '                                                   Ref: not detected\n' +
    '                                                                    [640]\n' +
    'Comment: BioFire Respiratory 2.1-EZ (PHRL)\n' +
    '        The Biofire FilmArray Respiratory Panel is an FDA-approved\n' +
    '        qualitative multiplexed nucleic acid test. Its performance\n' +
    '        has been verified by PHRL.\n' +
    '\n' +
    '        Mark Holodniy, MD, FACP, Director, VHA Public Health\n' +
    '        Laboratory, 3801 Miranda Avenue, Palo Alto, CA 94304,\n' +
    '        V21PHRL@va.gov CLIA #05D2125891\n' +
    '===============================================================================\n';

export default testData;
