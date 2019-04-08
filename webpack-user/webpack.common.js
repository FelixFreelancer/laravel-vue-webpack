var path = require("path");
var webpack = require("webpack");

const HtmlWebpackPlugin = require("html-webpack-plugin");
const ExtractCSS = require("extract-text-webpack-plugin");

function setPlugins() {
	var plugins = [
		new ExtractCSS({
			filename: "css/style.css"
		}),
		new webpack.NamedModulesPlugin(),
		new HtmlWebpackPlugin({
			title: "Global Parcel Forward - User",
			template: "./src-user/index.pug",
			filename:"index.html",
		})
	];
	return plugins;
}

/*
* 📣 : Pug Data & Variables
*/
function setPugData() {
	var pugData = {};
	pugData.img = "../img";
	return pugData;
}

module.exports = {
	entry: {
		user: ["./src-user/app.js"],
	},
	output: {
		path: path.resolve(__dirname, "../dist-user/"),
		filename: "app.js"
	},
	module: {
		loaders: [
			{
				test: /\.pug$/,
				use: [
					{
						loader: "html-loader"
					},
					{
						loader: "pug-html-loader",
						options: {
							pretty: true,
							data: setPugData()
						}
					}
				]
			},
			{
				test: /\.js$/,
				exclude: /(node_modules|bower_components)/,
				loader: "babel-loader"
			},
			{
				test: /\.vue$/,
				loader: "vue-loader"
			},
			{
				test: /\.(woff|woff2|eot|ttf)$/,
				loader: "file-loader",
				options: {
					outputPath: "fonts/",
					name: "[name].[ext]"
				}
			},
			{
				test: /\.(png|jpg|svg)$/,
				loader: "file-loader",
				options: {
					outputPath: "img/", //This will replace same image
					name: "[name].[ext]"
				}
			},
			{
				test: /\.scss$/,
				use: ["css-hot-loader"].concat(
					ExtractCSS.extract({
						fallback: "style-loader",
						use: [
							{ loader: "css-loader" },
							{
								loader: "postcss-loader",
								options: {
									config: {
										path: "./webpack-user/postcss.config.js"
									}
								}
							},
							{ loader: "sass-loader" }
						],
						publicPath: "../" //For Relative Path of Images
					})
				)
			},
			// TODO: Remove POST CSS Config from dev environment
			{
				test: /\.css$/,
				use: ["css-hot-loader"].concat(
					ExtractCSS.extract({
						fallback: "style-loader",
						use: [
							{ loader: "css-loader" },
							{
								loader: "postcss-loader",
								options: {
									config: {
										path: "./webpack-user/postcss.config.js"
									}
								}
							}
						],
						publicPath: "../"
					})
				)
			}
		]
	},
	plugins: setPlugins(),
	resolve: {
		alias: {
			Mixins: path.resolve(__dirname, "../src-user/mixins"),
			Components: path.resolve(__dirname, "../src-user/components"),
			Images: path.resolve(__dirname, "../src-user/static/img/"),
			Styles: path.resolve(__dirname, "../src-user/static/scss/"),
			Bus: path.resolve(__dirname, "../src-user/bus.js"),
			ScssConfig: path.resolve(__dirname, "../src-user/static/scss/vue-imports.scss"),
			vue: "vue/dist/vue.common.js"
		}
	}
};
