function Count(title, count, max)
{
if (title.value.length > max)
title.value = title.value.substring(0, max);
else
count.value = max - title.value.length;
}
