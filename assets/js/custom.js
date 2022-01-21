function number_format(n,s) {
    return n.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, s);
}