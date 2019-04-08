const merge = require("webpack-merge");
const common = require("./webpack.common.js");
var webpack = require("webpack");
var UglifyJSPlugin = require("uglifyjs-webpack-plugin");

module.exports = merge(common, {
	output: {
		publicPath: "",
		filename: "app.[hash].js"
	},
	plugins: [
		new webpack.DefinePlugin({
			"process.env.NODE_ENV": JSON.stringify("production"),
			ENV: JSON.stringify("production"),
			IS_DEV: JSON.stringify(false)
		}),
		new UglifyJSPlugin()
	]
});
