const merge = require("webpack-merge");
const common = require("./webpack.common.js");
var webpack = require("webpack");

module.exports = merge(common, {
	output: {
		publicPath: ""
	},
	plugins: [
		new webpack.DefinePlugin({
			"process.env.NODE_ENV": JSON.stringify("debug")
		})
	]
});
