function Count(keywords, count, max) {
    if (keywords.value.length > max)
        keywords.value = keywords.value.substring(0, max);
    else
        count.value = max - keywords.value.length;
}
