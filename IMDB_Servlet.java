import org.json.*;
import java.io.*;
import java.net.URL;
import java.net.URLConnection;
import java.util.*;
import javax.servlet.*;
import javax.servlet.http.*;
public class IMDB_Servlet extends HttpServlet {
		public void doGet(HttpServletRequest request, HttpServletResponse response)throws IOException, ServletException
		{
			String title = request.getParameter("title");
			String select = request.getParameter("select");
	  
			String urlstring = "http://cs-server.usc.edu:****/movie_get.php?title="+title+"&title_type="+select;

			URL url = new URL(urlstring);
			URLConnection urlConnection = url.openConnection();
			urlConnection.setAllowUserInteraction(false);
			InputStream urlStream = url.openStream();
			InputStreamReader isr = new InputStreamReader(urlStream, "UTF-8");
	        BufferedReader in = new BufferedReader(isr);
	        StringBuffer sb = new StringBuffer();       
	        String inputLine;
	        
	        while ((inputLine = in.readLine()) != null) {
	            sb.append(inputLine);
	        }
	        
	        String text = sb.toString();
	        String text1 = text.trim();

	

		  JSONObject job = XML.toJSONObject(text1);
		  String rs = job.toString();
		  String rs1=rs.replaceAll("\"total\":\"\\d\",", "");
		  String rs2=rs1.replaceAll(",\"stat\":\"ok\"}", "");
		  String rs3=rs2.replaceAll("rsp", "");
		  String rs4=rs3.replaceAll("\"\":", "");
		  String rs5=rs4.substring(1, rs4.length());

		  PrintWriter out = response.getWriter();
		  out.println(rs5);
			}
}
