<template>
  <h1>Hello Vue!</h1>
  <button @click="count++">{{ count }}</button>
</template>

<script>
import {ref} from 'vue'
import testData from "./testdata.js";
import {LabBuilder} from "./LabBuilder.js";


export default {

  setup() {
    const count = ref(0)
    const labBuilder = LabBuilder(testData)

    console.log(labBuilder.getLabCollection());

    return {
      count,
    }
  },
}


</script>
