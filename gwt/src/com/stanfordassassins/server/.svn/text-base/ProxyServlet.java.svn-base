package com.stanfordassassins.server;

import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStreamWriter;
import java.net.MalformedURLException;
import java.net.URI;
import java.net.URLConnection;

import javax.servlet.ServletException;
import javax.servlet.ServletInputStream;
import javax.servlet.ServletOutputStream;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

public class ProxyServlet extends HttpServlet {

	private static final long serialVersionUID = 3676141699245831874L;

	@Override
	protected void doGet(HttpServletRequest req, HttpServletResponse resp)
			throws ServletException, IOException {
		
		doStuff(req, resp);
	}

	private void doStuff(HttpServletRequest req, HttpServletResponse resp) throws IOException, MalformedURLException {
		String query = req.getQueryString();
		ServletInputStream requestInput = req.getInputStream();
		
//		URI uri = URI.create("http://stanfordassassins.com/gameserver.php" + (query != null ?("?" +  query) : ""));
		URI uri = URI.create("http://stanfordassassins.com/dev/gameserver.php?debug=2" + (query != null ? ("&" +  query) : ""));
		
		URLConnection conn = uri.toURL().openConnection();
		conn.setDoOutput(true);
	    OutputStreamWriter wr = new OutputStreamWriter(conn.getOutputStream());
	    int character;

	    System.out.println("Query: " + query);
	    System.out.print("Contents: ");
		while ((character = requestInput.read()) != -1) {
			wr.write(character);
			System.out.print((char)character);
		}
		wr.flush();
		System.out.println();
		
		
		InputStream i = conn.getInputStream();
		
		resp.setHeader("Content-Type", "application/json");
		ServletOutputStream os = resp.getOutputStream();
		while ((character = i.read()) != -1) {
			os.write(character);
			System.out.print((char)character);
		}
		System.out.println();
		os.close();
		i.close();
	}
	
	@Override
	protected void doPost(HttpServletRequest req, HttpServletResponse resp) throws ServletException, IOException {
		doStuff(req, resp);
	}

}
