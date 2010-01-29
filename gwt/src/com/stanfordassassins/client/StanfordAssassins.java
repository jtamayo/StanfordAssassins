package com.stanfordassassins.client;

import java.util.ArrayList;
import java.util.List;

import com.google.gwt.core.client.EntryPoint;
import com.google.gwt.core.client.GWT;
import com.google.gwt.core.client.JsArray;
import com.google.gwt.event.dom.client.ClickEvent;
import com.google.gwt.event.dom.client.ClickHandler;
import com.google.gwt.http.client.Request;
import com.google.gwt.http.client.RequestBuilder;
import com.google.gwt.http.client.RequestCallback;
import com.google.gwt.http.client.RequestException;
import com.google.gwt.http.client.Response;
import com.google.gwt.user.client.Window;
import com.google.gwt.user.client.ui.Button;
import com.google.gwt.user.client.ui.DialogBox;
import com.google.gwt.user.client.ui.HTML;
import com.google.gwt.user.client.ui.RootPanel;
import com.google.gwt.user.client.ui.TabPanel;
import com.google.gwt.user.client.ui.VerticalPanel;

/**
 * Entry point classes define <code>onModuleLoad()</code>.
 */
public class StanfordAssassins implements EntryPoint {

	private static final String CONTENT = "content";
	
//	public static String SERVER_URL = "http://stanfordassassins.com/gameserver.php";
	public static String SERVER_URL = "http://localhost:8888/proxy";
	private TabPanel tabPanel;
	
	private Join joinPage;
	
	// State for the game
	private Player player;
	private List<Game> games = new ArrayList<Game>();
	

	/**
	 * This is the entry point method.
	 */
	public void onModuleLoad() {

		RootPanel.get(CONTENT).add(new HTML("<h1>Loading...</h1>"));
		
		// Send request to server and catch any errors.
		String url = StanfordAssassins.SERVER_URL + "?cmd=" + ServerOperations.allData;
		RequestBuilder builder = new RequestBuilder(RequestBuilder.GET, url);

		try {
			Request request = builder.sendRequest(null, new RequestCallback() {
				public void onError(Request request, Throwable exception) {
					handleError(request, exception);
				}

				public void onResponseReceived(Request request, Response response) {
					if (200 == response.getStatusCode()) {
						Reply reply = Reply.asReply(response.getText());
						if (reply.getStatus() == ServerResults.OK) {
							login(reply.getPlayer(), reply.getGames());
						} else {
							// Authentication failed, redirect to home page
							System.out.println("Redirecting to " +GWT.getModuleBaseURL()+"../login.php" );
							redirect("http://stanfordassassins.com/login.php"); 
						}
					} else {
						handleError(response);
					}
				}
			});

		} catch (RequestException e) {
			handleError(e);
		}

	}

	private void handleError(RequestException e) {
		alertMessage("Error");
		e.printStackTrace();
	}

	protected void handleError(Request request, Throwable exception) {
		alertMessage("Error");
		exception.printStackTrace();
	}

	public void login(Player player, JsArray<Game> games) {
		RootPanel.get(CONTENT).clear();
		this.player = player;

		tabPanel = new TabPanel();
		if (player.getState() == PlayerState.NOTHING || player.getState() == PlayerState.WAITING) {
			// Display the join page only if the player is not playing a game
			joinPage = new Join(this, player);
			tabPanel.add(joinPage, "Join");
		}
		for (int i = 0; i < games.length(); i++) {
			Game game = games.get(i);
			this.games.add(game);
			tabPanel.add(new MyGame(game, this), game.getName());
		}
		
		tabPanel.add(new MyStats(), "My Stats");
		tabPanel.add(new LeaderBoard(), "Leader Board");
		
		tabPanel.selectTab(0);
		
		tabPanel.setWidth("600px");

		RootPanel.get(CONTENT).add(new Header(player.getName()));
		RootPanel.get(CONTENT).add(tabPanel);
		
	}
	
	public void onJoin(String alias) {
		// TODO: Check for SQL injection on alias
		String url = StanfordAssassins.SERVER_URL + "?cmd=" + ServerOperations.joinGame + "&alias=" + alias;

		RequestBuilder builder = new RequestBuilder(RequestBuilder.GET, url);
		try {
			Request request = builder.sendRequest(null, new RequestCallback() {
				public void onError(Request request, Throwable exception) {
					handleError(request, exception);
				}

				public void onResponseReceived(Request request, Response response) {
					if (200 == response.getStatusCode()) {
						// Do login, save the data
						String text = response.getText();
						Reply reply = Reply.asReply(text);
						if (reply.getStatus() == ServerResults.OK) {
							joinOK(reply.getPlayer());
						} else if (reply.getStatus() == ServerResults.ALIAS_TAKEN) {
							Window.alert("That alias has already been taken");
						} else if (reply.getStatus() == ServerResults.BAD_STATE) {
							Window.alert("You already joined the waiting list");
							// TODO: Check in what state the player is in, to show the appropriate message
						}
					} else {
						handleError(response);
					}
				}
			});

		} catch (RequestException e) {
			handleError(e);
		}
	}
	
	protected void handleError(Response response) {
		alertMessage("Error");
		System.out.println("Error: " + response);
	}

	public void alertMessage(String text) {
		final DialogBox dialogBox = new DialogBox();
		dialogBox.setText("Alert");
		final Button okButton = new Button("OK");
		
		VerticalPanel dialogVPanel = new VerticalPanel();
		dialogVPanel.addStyleName("dialogVPanel");
		dialogVPanel.add(new HTML(text));
		dialogVPanel.setHorizontalAlignment(VerticalPanel.ALIGN_RIGHT);
//		dialogVPanel.add(descriptionArea);
		dialogVPanel.add(okButton);
		dialogBox.setWidget(dialogVPanel);
		
		// Add a handler to close the DialogBox
		okButton.addClickHandler(new ClickHandler() {
			public void onClick(ClickEvent event) {
				dialogBox.hide();
			}
		});
		
		dialogBox.center();
	
	}

	protected void joinOK(Player player) {
		// Switch to the old page, and replace the player object
		this.player = player;
		joinPage.updatePlayer(player);
	}

	void handleError(Response request, Throwable exception) {
		alertMessage("We couldn't connect to the server.");
		exception.printStackTrace();
		System.err.println(request.getStatusCode());
		System.err.println(request.getHeadersAsString());
	}
	
	final native void redirect(String url)
	/*-{
		$wnd.location.replace(url);
	}-*/;

	public void assassinate(String codeword, final MyGame myGame, int gameId) {
		// TODO: Check for SQL injection on alias
		String url = StanfordAssassins.SERVER_URL + "?cmd=" + ServerOperations.reportAssassination + "&codeword=" + codeword + "&gameId=" + gameId;

		RequestBuilder builder = new RequestBuilder(RequestBuilder.GET, url);
		try {
			Request request = builder.sendRequest(null, new RequestCallback() {
				public void onError(Request request, Throwable exception) {
					handleError(request, exception);
				}

				public void onResponseReceived(Request request, Response response) {
					if (200 == response.getStatusCode()) {
						// Check if the codeword was the correct one
						String text = response.getText();
						Reply reply = Reply.asReply(text);
						System.out.println("Received response OK");
						if (reply.getStatus() == ServerResults.OK) {
							assassinateOK(reply.getGame(), myGame);
						} else if (reply.getStatus() == ServerResults.BAD_CODEWORD) {
							Window.alert("That codeword doesn't belong to any player");
						}
					} else {
						handleError(request);
					}
				}
			});

		} catch (RequestException e) {
			handleError(e);
		}
	}

	protected void handleError(Request request) {
		alertMessage("Error");
	}

	private void assassinateOK(Game newGame, MyGame gamePage) {
		// TODO: Clear the codeword textbox
		for (int i = 0;i < games.size(); i++) {
			Game oldGame = games.get(i);
			if (oldGame.getGameId() == newGame.getGameId()) {
				// Replace the old game, and update the page
				System.out.println("Found old game");
				games.set(i, newGame);
				gamePage.updateGame(newGame);
				// Now show the assassinated dialog, and everything else
				gamePage.showAssassinatedDialog(oldGame, newGame);
			}
		}
		
	}

}
