function Count(description, count, max)
{
if (description.value.length > max)
description.value = description.value.substring(0, max);
else
count.value = max - description.value.length;
}
