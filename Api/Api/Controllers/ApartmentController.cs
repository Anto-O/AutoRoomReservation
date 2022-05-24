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
                var apartment = Connection.QuerySingle<Apartment>("apartment_get", param, commandType: CommandType.StoredProcedure);
                var apartmentStr = JsonSerializer.Serialize(apartment);
                return Content($"{apartmentStr}");
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
                var apartment = Connection.Query<Apartment>("apartment_get_all", commandType: CommandType.StoredProcedure);
                var apartmentStr = JsonSerializer.Serialize(apartment);
                return Content($"{apartmentStr}");
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
                var apartmentStr = await reader.ReadToEndAsync();
                if (string.IsNullOrEmpty(apartmentStr))
                {
                    throw new Exception("La requete est vide");
                }
                var apartment = JsonSerializer.Deserialize<Apartment>(apartmentStr);
                if (apartment == null)
                {
                    throw new Exception("Les données sont vides ou malformé");
                }

                apartment.Id = Guid.NewGuid().ToString();
                DynamicParameters param = new();
                param.AddDynamicParams(apartment);
                Connection.Execute("apartment_insert", param, commandType: CommandType.StoredProcedure);
                return JsonSerializer.Serialize(new { Success = true, Error = "" });
            }
            catch (Exception e)
            {
                return JsonSerializer.Serialize(new { Success = false, Error = e.Message });
            }
        }

        [HttpGet]
        public string Remove([FromQuery] string Id)
        {
            try
            {
                DynamicParameters param = new();
                param.Add(nameof(Id), Id);
                var row = Connection.Execute("apartment_delete", param, commandType: CommandType.StoredProcedure);
                if (row < 1)
                {
                    return JsonSerializer.Serialize(new { Success = false, Error = "Aucun appartement ne correspond à cette id" });
                }
                return JsonSerializer.Serialize(new { Success = true });
            }
            catch (Exception e)
            {
                return JsonSerializer.Serialize(new { Success = false, Error = e.Message });
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
                var apartment = JsonSerializer.Deserialize<Apartment>(str);
                if (apartment.Id == null)
                {
                    throw new Exception("L'id est vide");
                }

                DynamicParameters param = new();
                param.AddDynamicParams(apartment);
                Connection.Execute("apartment_update", param, commandType: CommandType.StoredProcedure);
                return JsonSerializer.Serialize(new { Success = true, Error = "" });
            }
            catch (Exception e)
            {
                return JsonSerializer.Serialize(new { Success = false, Error = e.Message });
            }
        }
    }
}
