import {labs} from "./labs.short.js";
import ResultsBuilder from "./results-builder.js";
import DisplayBuilder from "./display-builder.js";

let labResults;
let unparsableRows;
let labels;
let panels;

const resultBuilder = new ResultsBuilder(labs);

resultBuilder.prepRows();
resultBuilder.build();
resultBuilder.sortLabs();
labResults = resultBuilder.getLabResults();
unparsableRows = resultBuilder.getUnparsableRows();
const displayBuilder = new DisplayBuilder(resultBuilder.getLabResults());
labels = await displayBuilder.getLabels();
panels = await displayBuilder.getPanels();
const dateTimeHeaders = displayBuilder.getDateTimeHeaders();

// Todo:
//log unmatched labs w/ fetch API or Inertia
// import { router } from '@inertiajs/vue3'
//
// router.post('/users', {
//     name: 'John Doe',
//     email: 'john.doe@example.com',
// })

export {labResults, unparsableRows, labels, panels, dateTimeHeaders};
