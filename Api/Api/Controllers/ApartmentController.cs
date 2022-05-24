using Dapper;
using Microsoft.AspNetCore.Mvc;
using Models;
using MySql.Data.MySqlClient;
using System.Data;
using System.Text.Json;

namespace Api.Controllers
{
    [Route("[controller]/[action]")]
    [ApiController]
    public class ApartmentController : Controller
    {

        private readonly IConfiguration _config;

        private readonly string CS;

        private MySqlConnection Connection { get; set; }

        public ApartmentController(IConfiguration config)
        {
            _config = config;
            CS = _config.GetConnectionString("DB");
            Connection = new(CS);
        }

        [HttpGet]
        public IActionResult Get([FromQuery] string Id)
        {
            try
            {
                if (string.IsNullOrWhiteSpace(Id))
                {
                    throw new Exception("L'id est null");
                }

                DynamicParameters param = new();
                param.Add(nameof(Id), Id);
                var user = Connection.QuerySingle<Apartment>("user_get", param, commandType: CommandType.StoredProcedure);
                var userStr = JsonSerializer.Serialize(user);
                return Content($"{userStr}");
            }
            catch (Exception e)
            {
                return Content("");
            }
        }
        
        [HttpGet]
        public IActionResult GetAll()
        {
            try
            {
                var user = Connection.Query<User>("user_get_all", commandType: CommandType.StoredProcedure);
                var userStr = JsonSerializer.Serialize(user);
                return Content($"{userStr}");
            }
            catch (Exception e)
            {
                return Content("");
            }
        }

        [HttpPost]
        public async Task<string> Add()
        {
            try
            {
                StreamReader reader = new(Request.Body);
                var str = await reader.ReadToEndAsync();
                if (string.IsNullOrEmpty(str))
                {
                    throw new Exception("La requete est vide");
                }
                var user = JsonSerializer.Deserialize<User>(str);
                if (user == null)
                {
                    throw new Exception("Le body");
                }

                user.Id = Guid.NewGuid().ToString();
                DynamicParameters param = new();
                param.AddDynamicParams(user);
                Connection.Execute("user_insert", param, commandType: CommandType.StoredProcedure);
                return JsonSerializer.Serialize(new { Success = true, Error = "" });
            }
            catch (Exception e)
            {
                return JsonSerializer.Serialize(new { Success = false, Error = e.Message });
            }
        }

        [HttpGet]
        public bool Remove([FromQuery] string Id)
        {
            try
            {
                DynamicParameters param = new();
                param.Add(nameof(Id), Id);
                var user = Connection.Execute("user_delete", param, commandType: CommandType.StoredProcedure);
                var userStr = JsonSerializer.Serialize(user);
                return true;
            }
            catch (Exception e)
            {
                return false;
            }
        }

        public async Task<string> Update()
        {
            try
            {
                StreamReader reader = new(Request.Body);
                var str = await reader.ReadToEndAsync();
                if (string.IsNullOrEmpty(str))
                {
                    throw new Exception("La requete est vide");
                }
                var user = JsonSerializer.Deserialize<User>(str);
                if (user.Id == null)
                {
                    throw new Exception("L'id est vide");
                }

                DynamicParameters param = new();
                param.AddDynamicParams(user);
                Connection.Execute("user_update", param, commandType: CommandType.StoredProcedure);
                return JsonSerializer.Serialize(new { Success = true, Error = "" });
            }
            catch (Exception e)
            {
                return JsonSerializer.Serialize(new { Success = false, Error = e.Message });
            }
        }
    }
}
