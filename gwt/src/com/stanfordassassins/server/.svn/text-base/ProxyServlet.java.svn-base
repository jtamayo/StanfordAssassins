package com.stanfordassassins.server;

import java.io.IOException;
import java.io.InputStream;
import java.net.URI;
import java.net.URLConnection;

import javax.servlet.ServletException;
import javax.servlet.ServletOutputStream;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

public class ProxyServlet extends HttpServlet {

	private static final long serialVersionUID = 3676141699245831874L;

	@Override
	protected void doGet(HttpServletRequest req, HttpServletResponse resp)
			throws ServletException, IOException {
		
		String query = req.getQueryString();
//		URI uri = URI.create("http://stanfordassassins.com/gameserver.php" + (query != null ?("?" +  query) : ""));
		URI uri = URI.create("http://stanfordassassins.com/gameserver.php?debug=1" + (query != null ? ("&" +  query) : ""));
		System.out.println("Query: " + query);
		
		URLConnection conn = uri.toURL().openConnection();
		conn.connect();
		
		InputStream i = conn.getInputStream();
		
		int character;
		
		ServletOutputStream os = resp.getOutputStream();
		while ((character = i.read()) != -1) {
			os.write(character);
			System.out.print((char)character);
		}
		System.out.println();
		os.close();
		i.close();
	}

}
