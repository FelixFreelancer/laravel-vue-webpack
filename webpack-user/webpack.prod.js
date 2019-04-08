const merge = require("webpack-merge");
const common = require("./webpack.common.js");
var webpack = require("webpack");
var UglifyJSPlugin = require("uglifyjs-webpack-plugin");
var ManifestPlugin = require("webpack-manifest-plugin");

module.exports = merge(common, {
	output: {
		publicPath: "https://www.globalparcelforward.com/user/",
		filename: "app.[hash].js"
	},
	plugins: [
		new ManifestPlugin(),
		new webpack.DefinePlugin({
			"process.env.NODE_ENV": JSON.stringify("production"),
			ENV: JSON.stringify("production"),
			IS_DEV: JSON.stringify(false)
		}),
		new UglifyJSPlugin()
	]
});
