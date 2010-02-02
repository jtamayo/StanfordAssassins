package com.stanfordassassins.client;

import com.google.gwt.core.client.GWT;
import com.google.gwt.core.client.GWT.UncaughtExceptionHandler;
import com.google.gwt.http.client.Request;
import com.google.gwt.http.client.RequestBuilder;
import com.google.gwt.http.client.RequestCallback;
import com.google.gwt.http.client.RequestException;
import com.google.gwt.http.client.Response;
import com.google.gwt.http.client.URL;

public class ExceptionHandler implements UncaughtExceptionHandler {

	public void onUncaughtException(Throwable e) {
		try {
			GWT.log("Uncaught exception", e); // So that we can see the error in the console
			String url = StanfordAssassins.SERVER_URL; 
			StackTraceElement[] stackTrace = e.getStackTrace();
			StringBuffer message = new StringBuffer();
			message.append(e.getMessage() + "\n");
			for (StackTraceElement stackTraceElement : stackTrace) {
				message.append(stackTraceElement);
				message.append("\n");
			}
			String data = "cmd=" + ServerOperations.reportError + "&error=" + message;

			data = URL.encode(data);
			RequestBuilder builder = new RequestBuilder(RequestBuilder.POST, url);
			builder.setHeader("Content-Type", "application/x-www-form-urlencoded");
			try {
				builder.sendRequest(data, new RequestCallback() {
					public void onError(Request request, Throwable exception) {
						// Do nothing, there's nothing we can do
					}

					public void onResponseReceived(Request request, Response response) {
						// I don't care about the response, all that matters is that the trace gets to the server
					}
				});

			} catch (RequestException _) {
				// Once again, doesn't matter
			}
		} catch (Throwable _) {
			// Do nothing, in case things go bad in the try/catch
		}
		if (e instanceof Error) {
			throw (Error) e;
		} else if (e instanceof RuntimeException) {
			throw (RuntimeException) e;
		} else {
			throw new RuntimeException(e);
		}
	}

}
