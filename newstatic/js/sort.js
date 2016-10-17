/**
 * Leisure E-mail:sxxhlx@foxmail.com
 * @param {Array} arr 一个待排序的数组
 */
function sort(arr) {
	for (var i = 0; i < arr.length; i++) {
		for (var j = 0; j < arr.length - i - 1; j++) {
			if (arr[j] < arr[j + 1]) {
				var temp = arr[j];
				arr[j] = arr[j + 1];
				arr[j + 1] = temp;
			}
		}
	}
	return arr;
}
var demoArray = [35, 74, 3, 5, 2, 6, 78, 89,103,5426,4];
console.log(sort(demoArray));
