function Count(description_short, count, max)
{
if (description_short.value.length > max)
description_short.value = description_short.value.substring(0, max);
else
count.value = max - description_short.value.length;
}
