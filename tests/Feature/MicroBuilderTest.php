<?php

use App\Services\MicroBuilder;

it('logs unrecognized micros', function () {
    $rawMicro = <<<'RAWMICRO'
                                    ---- MICROBIOLOGY ----
    Printed at:
    MY VA MEDICAL CENTER [CLIA# 01A0000000]
    123 WEST MARTIN LUTHER KING BLVD CITY, ST 01245-6789
    As of: Sep 09, 2023@07:58



    Reporting Lab: FRESNO VA MEDICAL CENTER [CLIA# 01A0000000]
                   2615 EAST CLINTON AVE FRESNO, CA 93703-2223

    Accession [UID]: MICR 00 0000 [1234567890]  Received: Sep 04, 2023@17:31
    Collection sample: URINE               Collection date: Sep 04, 2023 16:53
    Provider: DOE,JOHN


     Test(s) ordered: C&S,FAKE...................... completed: Sep 06, 2023 14:27

    * BACTERIOLOGY FINAL REPORT => Sep 06, 2023 14:27   TECH CODE: 99999
    Bacteriology Remark(s):
      NO GROWTH 2 DAYS

    =--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--
    Performing Laboratory:
    Bacteriology Report Performed By:
    MY VA MEDICAL CENTER [CLIA# 01A0000000]
    123 WEST MARTIN LUTHER KING BLVD CITY, ST 01245-6789

    ===============================================================================
    RAWMICRO;
    $microBuilder = new MicroBuilder($rawMicro);
    $microBuilder->build();

    $this->assertDatabaseHas('unrecognized_micros', ['name' => 'C&S,FAKE']);
});
