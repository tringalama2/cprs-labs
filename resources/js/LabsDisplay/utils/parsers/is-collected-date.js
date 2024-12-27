export default function isResult(row) {
    return !row.startsWith(' ') &&
        row.split(/(\s){2,}/).length > 2 &&
        /\[([0-9]+?)]$/.test(row);
}
