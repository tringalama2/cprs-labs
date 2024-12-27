export default class Lab {
    constructor(specimenUniqueId, name, result, collectionDate, releasedDate, flag, units, referenceRange, specimen, orderingProvider, siteCode) {
        this.specimenUniqueId = specimenUniqueId;
        this.name = name;
        this.result = result;
        this.collectionDate = collectionDate;
        this.releasedDate = releasedDate;
        this.flag = flag;
        this.units = units;
        this.referenceRange = referenceRange;
        this.specimen = specimen;
        this.orderingProvider = orderingProvider;
        this.siteCode = siteCode;
    }

    result() {
        return {
            specimen_unique_id: this.specimenUniqueId,
            name: this.name,
            result: this.result,
            collection_date: this.collectionDate,
            released_date: this.releasedDate,
            flag: this.flag,
            units: this.units,
            reference_range: this.referenceRange,
            specimen: this.specimen,
            ordering_provider: this.orderingProvider,
            site_code: this.siteCode,
        };
    }
}

