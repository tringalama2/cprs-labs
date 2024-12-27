export default function intersectObjects(obj1, obj2) {
    const result = {};
    for (const key in obj1) {
        if (obj2.hasOwnProperty(key)) {
            result[key] = obj1[key];
        }
    }

    return result;
}
