export default function isOverflowRow(row) {
    return /^(\s)+(.)*(\[([0-9]+?)]$)/.test(row);
}


