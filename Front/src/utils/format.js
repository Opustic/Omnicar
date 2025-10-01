export function formatCFA (value) {

    if (value == null) return "";
    return value.toLocaleString('fr-FR', { minimumFractionDigits: 0 }) + " FCFA"
}